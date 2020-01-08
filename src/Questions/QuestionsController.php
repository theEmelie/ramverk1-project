<?php

namespace Anax\Questions;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

use Anax\Questions\HTMLForm\CreateQuestionForm;
use Anax\Answers\HTMLForm\CreateAnswerForm;
use Anax\TagsQuestions\TagsQuestions;
use Anax\qComments\HTMLForm\CreateQCommentForm;
use Anax\aComments\HTMLForm\CreateACommentForm;

use Anax\Tags\Tags;
use Anax\User\User;
use Anax\Answers\Answers;
use Anax\QComments\QComments;
use Anax\AComments\AComments;
use Anax\Textfilter\MyTextfilter;

use Anax\QuestionVotes\QuestionVotes;
use Anax\AnswerVotes\AnswerVotes;
use Anax\AnswerCommentVotes\AnswerCommentVotes;
use Anax\QuestionCommentVotes\QuestionCommentVotes;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class QuestionsController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
    * Function to run markdown textfilter on text
    *  @return string
    */
    public function runMarkdown($text)
    {
        $myTextFilter = new MyTextfilter();

        $text = $myTextFilter->parse($text, "markdown");
        return $text;
    }

    public function indexAction() : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $question = new Questions();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->getAllQuestions();

        foreach ($questions as $que) {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $user->getUserDataById($que->userId);
            $que->username = $user->acronym;

            $que->tagNames = $this->getTagNames($que->id);
            $que->numberOfAnswers = $question->getNumberOfAnswers($this->di, $que->id);
            $que->voteCount = $question->getVoteCount($this->di, $que->id);
        }

        $page->add("questions/viewAll", [
            "questions" => $questions
        ]);

        return $page->render([
            "title" => "View Questions",
        ]);
    }

    public function askAction() : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $form = new CreateQuestionForm($this->di);
        $form->check();

        $page->add("questions/ask", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "View Question",
        ]);
    }

    public function viewTagAction($tagName) : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $tagObj = new Tags();
        $tagObj->setDb($this->di->get("dbqb"));
        $res = $tagObj->getTagByName($tagName);

        $tqObj = new TagsQuestions();
        $tqObj->setDb($this->di->get("dbqb"));
        $tagsQuestions = $tqObj->getAllByTagId($tagObj->id);

        $questions = array();

        foreach ($tagsQuestions as $tqs) {
            $question = new Questions();
            $question->setDb($this->di->get("dbqb"));
            $res = $question->getQuestionById($tqs->questionId);

            // Add username to array
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $res = $user->getUserDataById($question->userId);
            $question->username = $user->acronym;

            // If we want to add all tags for this question we have to search
            // through tagQuestions and tags as in the view action.
            $tagNames = $this->getTagNames($question->id);
            $question->tagNames = $tagNames;
            array_push($questions, $question);
        }

        $page->add("questions/viewAllTag", [
            "questions" => $questions
        ]);

        return $page->render([
            "title" => "View Questions for " . $tagName,
        ]);
    }

    public function getTagNames($qid)
    {
        // Get tags for question
        $tqObj = new TagsQuestions();
        $tqObj->setDb($this->di->get("dbqb"));
        $tagsQuestions = $tqObj->getAllByQuestionId($qid);
        $tagNames = array();

        foreach ($tagsQuestions as $tqs) {
            $tagObj = new Tags();
            $tagObj->setDb($this->di->get("dbqb"));
            $tagObj->getTagById($tqs->tagId);
            array_push($tagNames, $tagObj->tag);
        }

        return $tagNames;
    }

    public function viewAction($qid, $sort)
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userSessionId = $session->get("userId");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $question = new Questions();
        $question->setDb($this->di->get("dbqb"));
        $res = $question->getQuestionById($qid);

        // Run markdown on view questions
        $question->text = $this->runMarkdown($question->text);

        $questionVotes = new QuestionVotes();
        $questionVotes->setDb($this->di->get("dbqb"));
        $res = $questionVotes->countVoteForQid($this->di, $qid);

        if ($res->voteCount == null) {
            $res->voteCount = 0;
        }

        $question->voteCount = $res->voteCount;

        // Find all comments
        $qComment = new QComments();
        $qComment->setDb($this->di->get("dbqb"));
        $qComments = $qComment->getAllqCommentsByQuestionId($qid);

        foreach ($qComments as $com) {
            $comUser = new User();
            $comUser->setDb($this->di->get("dbqb"));
            $res = $comUser->getUserDataById($com->userId);

            $com->username = $comUser->acronym;

            // Run markdown on question comments text
            $com->text = $this->runMarkdown($com->text);

            $questionCommentVotes = new QuestionCommentVotes();
            $questionCommentVotes->setDb($this->di->get("dbqb"));
            $res = $questionCommentVotes->countVoteForQCid($this->di, $com->id);

            if ($res->voteCount == null) {
                $res->voteCount = 0;
            }

            $com->voteCount = $res->voteCount;
        }

        $question->comments = $qComments;

        // Add username to array
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->getUserDataById($question->userId);
        $question->username = $user->acronym;

        // If we want to add all tags for this question we have to search
        // through tagQuestions and tags as in the view action.
        $tagNames = $this->getTagNames($question->id);
        $question->tagNames = $tagNames;

        // Now get all answers
        $answer = new Answers();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->getAllAnswersByQuestionId($question->id);

        foreach ($answers as $ans) {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $res = $user->getUserDataById($ans->userId);
            $ans->username = $user->acronym;

            // Find all comments for answer
            $aComment = new AComments();
            $aComment->setDb($this->di->get("dbqb"));
            $aComments = $aComment->getAllaCommentsByAnswerId($ans->id);

            // Run markdown on answers text
            $ans->text = $this->runMarkdown($ans->text);

            foreach ($aComments as $com) {
                $comUser = new User();
                $comUser->setDb($this->di->get("dbqb"));
                $res = $comUser->getUserDataById($com->userId);

                $com->username = $comUser->acronym;

                // Run markdown on answer comments text
                $com->text = $this->runMarkdown($com->text);

                $answerCommentVotes = new AnswerCommentVotes();
                $answerCommentVotes->setDb($this->di->get("dbqb"));
                $res = $answerCommentVotes->countVoteForACid($this->di, $com->id);

                if ($res->voteCount == null) {
                    $res->voteCount = 0;
                }

                $com->voteCount = $res->voteCount;
            }

            $answerVotes = new AnswerVotes();
            $answerVotes->setDb($this->di->get("dbqb"));
            $res = $answerVotes->countVoteForAid($this->di, $ans->id);

            if ($res->voteCount == null) {
                $res->voteCount = 0;
            }

            $ans->voteCount = $res->voteCount;

            $ans->comments = $aComments;
        }

        if ($sort == 'date') {
            usort($answers, array($this, 'sortByDateDESC'));
        } else if ($sort == 'rank') {
            usort($answers, array($this, 'sortByRankDESC'));
        }
        // var_dump($answers);

        if ($question->userId == $userSessionId) {
            $isAuthor = true;
        } else {
            $isAuthor = false;
        }

        $page->add("questions/view", [
            "question" => $question,
            "answers" => $answers,
            "isAuthor" => $isAuthor
        ]);

        return $page->render([
            "title" => "View Question",
        ]);
    }

    private static function sortByDateDESC($first, $second)
    {
        return $first->updated < $second->updated;
    }

    private static function sortByRankDESC($first, $second)
    {
        return $first->voteCount < $second->voteCount;
    }

    public function answerAction($questionId) : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $form = new CreateAnswerForm($this->di, $questionId);
        $form->check();

        $page->add("questions/answer", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Answer Question",
        ]);
    }

    public function commentAction($questionId) : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $form = new CreateQCommentForm($this->di, $questionId);
        $form->check();

        $page->add("questions/qComment", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Comment Question",
        ]);
    }

    public function answerCommentAction($questionId, $answerId) : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $form = new CreateACommentForm($this->di, $questionId, $answerId);
        $form->check();

        $page->add("questions/aComment", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Comment Answer",
        ]);
    }

    public function markAcceptedAnswerAction($questionId, $answerId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $answerObj = new Answers();
        $answerObj->setDb($this->di->get("dbqb"));
        $answerObj->getAnswerById($answerId);

        $answerObj->accepted = true;
        $answerObj->save();

        $response->redirect("questions/view/$questionId/date");
    }

    public function upVoteAction($questionId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $qvObj = new QuestionVotes();
        $qvObj->setDb($this->di->get("dbqb"));
        $qvObj->upVoteQuestion($this->di, $questionId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }

    public function downVoteAction($questionId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $qvObj = new QuestionVotes();
        $qvObj->setDb($this->di->get("dbqb"));
        $qvObj->downVoteQuestion($this->di, $questionId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }

    public function upVoteAnswerAction($questionId, $answerId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $avObj = new AnswerVotes();
        $avObj->setDb($this->di->get("dbqb"));
        $avObj->upVoteAnswer($this->di, $answerId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }

    public function downVoteAnswerAction($questionId, $answerId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $avObj = new AnswerVotes();
        $avObj->setDb($this->di->get("dbqb"));
        $avObj->downVoteAnswer($this->di, $answerId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }

    public function upVoteAnswerCommentAction($questionId, $acId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $obj = new AnswerCommentVotes();
        $obj->setDb($this->di->get("dbqb"));
        $obj->upVoteAnswerComment($this->di, $acId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }

    public function downVoteAnswerCommentAction($questionId, $acId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $obj = new AnswerCommentVotes();
        $obj->setDb($this->di->get("dbqb"));
        $obj->downVoteAnswerComment($this->di, $acId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }

    public function upVoteQuestionCommentAction($questionId, $qcId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $obj = new QuestionCommentVotes();
        $obj->setDb($this->di->get("dbqb"));
        $obj->upVoteQuestionComment($this->di, $qcId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }

    public function downVoteQuestionCommentAction($questionId, $qcId)
    {
        $page = $this->di->get("page");
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $username = $session->get("acronym");
        $userId = $session->get("userId");

        if ($username == "") {
            $response->redirect("user/login");
        }

        $obj = new QuestionCommentVotes();
        $obj->setDb($this->di->get("dbqb"));
        $obj->downVoteQuestionComment($this->di, $qcId, $userId);

        $response->redirect("questions/view/$questionId/date");
    }





    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
        return;
    }
}
