<?php

namespace Anax\Frontpage;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

use Anax\Tags\Tags;
use Anax\TagsQuestions\TagsQuestions;
use Anax\User\User;
use Anax\Questions\Questions;
use Anax\Textfilter\MyTextfilter;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class FrontpageController implements ContainerInjectableInterface
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

    // public function sortByDateDESC($first, $second)
    // {
    //     return $first->updated > $second->updated;
    // }

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

    public function indexAction() : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $question = new Questions();
        $question->setDb($this->di->get("dbqb"));
        $limit = 3;
        $questions = $question->getAllQuestionsByDateDesc($limit);

        foreach ($questions as $que) {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $user->getUserDataById($que->userId);
            $que->username = $user->acronym;

            $que->tagNames = $this->getTagNames($que->id);
        }

        // Get most popular tags
        $limit = 3;
        $tagObj = new Tags();
        $tagObj->setDb($this->di->get("dbqb"));
        $popularTags = $tagObj->getMostPopularTags($this->di, $limit);
        // var_dump($res);

        // Get most active users
        $userActivity = $user->getUserActivity($this->di, $limit);
        // var_dump($userActivity);

        $page->add("frontpage/view", [
            "questions" => $questions,
            "popularTags" => $popularTags,
            "userActivity" => $userActivity
        ]);

        return $page->render([
            "title" => "FurQuestions",
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
