<?php

namespace Anax\Questions;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Questions\HTMLForm\CreateQuestionForm;
use Anax\TagsQuestions\TagsQuestions;
use Anax\Tags\Tags;
use Anax\User\User;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class QuestionsController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


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
            $res = $user->getUserDataById($que->user_id);
            $que->username = $user->acronym;

            // // Get tags for question
            // $tqObj = new TagsQuestions();
            // $tqObj->setDb($this->di->get("dbqb"));
            // $tagsQuestions = $tqObj->getAllByQuestionId($que->id);
            //
            // $tagNames = array();
            //
            // foreach ($tagsQuestions as $tqs) {
            //     $tagObj = new Tags();
            //     $tagObj->setDb($this->di->get("dbqb"));
            //     $res = $tagObj->getTagById($tqs->tag_id);
            //     array_push($tagNames, $tagObj->tag);
            // }
            $que->tagNames = $this->getTagNames($que->id);

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
            $res = $question->getQuestionById($tqs->question_id);

            // Add username to array
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $res = $user->getUserDataById($question->user_id);
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
            $res = $tagObj->getTagById($tqs->tag_id);
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

        // Add username to array
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->getUserDataById($question->user_id);
        $question->username = $user->acronym;

        // If we want to add all tags for this question we have to search
        // through tagQuestions and tags as in the view action.
        $tagNames = $this->getTagNames($question->id);
        $question->tagNames = $tagNames;

        // Now get all answers

        $page->add("questions/view", [
            "question" => $question
        ]);

        return $page->render([
            "title" => "View Question",
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
