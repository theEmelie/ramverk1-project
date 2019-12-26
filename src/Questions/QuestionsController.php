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

            // Run markdown on questions text and title
            $que->text = $this->runMarkdown($que->text);
            // $que->title = $this->runMarkdown($que->title);
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

    public function viewAction($qid)
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
        $res = $question->getQuestionById($qid);

        // Run markdown on view questions
        $question->text = $this->runMarkdown($question->text);

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
            }

            $ans->comments = $aComments;
        }

        $page->add("questions/view", [
            "question" => $question,
            "answers" => $answers
        ]);

        return $page->render([
            "title" => "View Question",
        ]);
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
