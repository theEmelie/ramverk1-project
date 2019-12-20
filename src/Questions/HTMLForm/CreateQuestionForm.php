<?php

namespace Anax\Questions\HTMLForm;

use Anax\HTMLForm\FormModel;
use Anax\Questions\Questions;
use Anax\Tags\Tags;
use Anax\TagsQuestions\TagsQuestions;
use Psr\Container\ContainerInterface;

/**
 * FormModel implementation.
 */
class CreateQuestionForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "title" => [
                    "type"        => "text",
                    "validation"  => ["not_empty" ]
                ],

                "text" => [
                    "type"        => "textarea",
                    "validation"  => ["not_empty" ]
                ],

                "tags" => [
                    "type"        => "text",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Create Question",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $title = $this->form->value("title");
        $text  = $this->form->value("text");
        $tagString  = $this->form->value("tags");

        $response = $this->di->get("response");
        $session  = $this->di->get("session");

        if ($session->has("user_id")) {
            $question = new Questions();
            $question->setDb($this->di->get("dbqb"));

            $question->user_id = $session->get("user_id");
            $question->title = $title;
            $question->text = $text;
            $question->created = date("Y-m-d H:i:s");
            $question->updated = $question->created;
            $question->active = $question->created;
            $question->save();

            $tags = array_unique(array_map("trim", explode(",", $tagString)));
            foreach ($tags as $tagName) {
                if ($tagName != "")
                {
                    $tagObj = new Tags();
                    $tagObj->setDb($this->di->get("dbqb"));
                    if (!$tagObj->tagExists($tagName)) {
                        // Tag doesnt exists, add it
                        $tagObj->tag = $tagName;
                        $tagObj->save();
                    }
                    // Add entry into TagQuestions table.
                    $tagQuestion = new TagsQuestions();
                    $tagQuestion->setDb($this->di->get("dbqb"));
                    $tagQuestion->tag_id = $tagObj->id;
                    $tagQuestion->question_id = $question->id;
                    $tagQuestion->save();
                }
            }
            $response->redirect("questions");
        } else {
            return false;
        }
    }
}
