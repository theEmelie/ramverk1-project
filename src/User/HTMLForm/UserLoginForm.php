<?php

namespace Anax\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Anax\User\User;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class UserLoginForm extends FormModel
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
                "user" => [
                    "type"        => "text",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "password" => [
                    "type"        => "password",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Login",
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
        $acronym       = $this->form->value("user");
        $password      = $this->form->value("password");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->verifyPassword($acronym, $password);

        $session = $this->di->get("session");
        $response = $this->di->get("response");

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("User or password did not match.");
            $session->set("acronym", "");
            $session->set("userId", "");
            return false;
        }

        $this->form->addOutput("User " . $user->acronym . " logged in.");
        $session->set("acronym", $user->acronym);
        $session->set("userId", $user->id);
        $response->redirect("user");
    }
}
