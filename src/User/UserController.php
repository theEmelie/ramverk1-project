<?php

namespace Anax\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

use Anax\User\HTMLForm\UserLoginForm;
use Anax\User\HTMLForm\CreateUserForm;
use Anax\User\HTMLForm\UserChangePasswordForm;
use Anax\User\HTMLForm\UserChangeEmailForm;

use Anax\Answers\Answers;
use Anax\Questions\Questions;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */
    //private $data;



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     ;
    // }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->getUserData($username);

        $grav = $this->getGravatar($user->email);

        // Get all questions asked by user

        // Get all answers posted by user

        $page->add("user/view", [
            "username" => $user->acronym,
            "email" => $user->email,
            "grav" => $grav,
        ]);

        return $page->render([
            "title" => "View User",
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new UserLoginForm($this->di);
        $form->check();

        $page->add("user/login", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Login",
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateUserForm($this->di);
        $form->check();

        $page->add("user/create", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create User",
        ]);
    }

    public function logoutAction()
    {
        $response = $this->di->get("response");
        $session = $this->di->get("session");
        $session->destroy();
        $response->redirect("");
    }

    public function changePasswordAction()
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $form = new UserChangePasswordForm($this->di);
        $form->check();

        $page->add("user/changePassword", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Change Password",
        ]);
    }

    public function changeEmailAction()
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("acronym");

        if ($username == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $form = new UserChangeEmailForm($this->di);
        $form->check();

        $page->add("user/changeEmail", [
        ]);

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Change Email",
        ]);
    }

    public function viewAction($username)
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $loggedInUser = $session->get("acronym");

        if ($loggedInUser == "") {
            $response = $this->di->get("response");
            $response->redirect("user/login");
        }

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->getUserData($username);

        $question = new Questions();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->getAllQuestionsByUserId($user->id);

        $answer = new Answers();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->getAllAnswersByUserId($user->id);

        foreach ($answers as $ans) {
            $res = $question->getQuestionById($ans->questionId);
            $ans->questionTitle = $question->title;
        }

        $page->add("user/viewPosts", [
            "questions" => $questions,
            "answers" => $answers,
            "username" => $username
        ]);

        return $page->render([
            "title" => "Viewing Posts",
        ]);
    }

    /**
    * Get either a Gravatar URL or complete image tag for a specified email address.
    *
    * @param string $email The email address
    * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
    * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
    * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
    * @param boole $img True to return a complete IMG tag False for just the URL
    * @param array $atts Optional, additional key/value attributes to include in the IMG tag
    * @return String containing either just a URL or a complete image tag
    * @source https://gravatar.com/site/implement/images/php/
    *
    * @SuppressWarnings(PHPMD.ShortVariable)
    *
    */
    public function getGravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
}
