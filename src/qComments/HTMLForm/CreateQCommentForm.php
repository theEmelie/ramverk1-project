<?php

namespace Anax\QComments\HTMLForm;

use Anax\HTMLForm\FormModel;
use Anax\QComments\QComments;
use Psr\Container\ContainerInterface;

/**
 * FormModel implementation.
 */
class CreateQCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $questionId)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "text" => [
                    "type"        => "textarea",
                    "validation"  => ["not_empty" ]
                ],

                "questionId" => [
                    "type"        => "hidden",
                    "value"       => $questionId
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Submit Answer",
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
        $text        = $this->form->value("text");
        $questionId  = $this->form->value("questionId");

        $response = $this->di->get("response");
        $session  = $this->di->get("session");

        if ($session->has("userId")) {
            $qComments = new QComments();
            $qComments->setDb($this->di->get("dbqb"));

            $qComments->userId = $session->get("userId");
            $qComments->text = $text;
            $qComments->questionId = $questionId;
            $qComments->created = date("Y-m-d H:i:s");
            $qComments->updated = $qComments->created;
            $qComments->active = $qComments->created;
            $qComments->save();

            $response->redirect("questions/view/$questionId");
        } else {
            return false;
        }
    }
}
