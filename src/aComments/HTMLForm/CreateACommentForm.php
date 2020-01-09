<?php

namespace Anax\AComments\HTMLForm;

use Anax\HTMLForm\FormModel;
use Anax\AComments\AComments;
use Psr\Container\ContainerInterface;

/**
 * FormModel implementation.
 */
class CreateACommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $questionId, $answerId)
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

                "answerId" => [
                    "type"        => "hidden",
                    "value"       => $answerId
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
        $answerId    = $this->form->value("answerId");

        $response = $this->di->get("response");
        $session  = $this->di->get("session");

        if ($session->has("userId")) {
            $aComments = new AComments();
            $aComments->setDb($this->di->get("dbqb"));

            $aComments->userId = $session->get("userId");
            $aComments->text = $text;
            $aComments->answerId = $answerId;
            $aComments->created = date("Y-m-d H:i:s");
            $aComments->updated = $aComments->created;
            $aComments->active = $aComments->created;
            $aComments->save();

            $response->redirect("questions/view/$questionId/date");
            // var_dump($aComments);
        } else {
            return false;
        }
    }
}
