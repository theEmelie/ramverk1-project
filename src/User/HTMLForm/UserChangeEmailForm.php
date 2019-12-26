<?php

namespace Anax\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Anax\User\User;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class UserChangeEmailForm extends FormModel
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
                "email" => [
                    "type"        => "text",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Change Email",
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
        $email = $this->form->value("email");

        $session = $this->di->get("session");
        $response = $this->di->get("response");
        $username = $session->get("acronym");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->getUserData($username);
        $user->email = $email;
        $user->updated = date("Y-m-d H:i:s");
        $user->save();

        $this->form->addOutput("Email has been changed.");
        $response->redirect("user");
    }
}
