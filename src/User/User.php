<?php

namespace Anax\User;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class User extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "User";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $acronym;
    public $email;
    public $password;
    public $created;
    public $updated;
    public $deleted;
    public $active;

    /**
     * Set the password.
     *
     * @param string $password the password to use.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify the acronym and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $acronym  acronym to check.
     * @param string $password the password to use.
     *
     * @return boolean true if acronym and password matches, else false.
     */
    public function verifyPassword($acronym, $password)
    {
        $this->find("acronym", $acronym);
        return password_verify($password, $this->password);
    }

    public function getUserData($acronym)
    {
        $this->find("acronym", $acronym);
    }

    public function getUserDataById($id)
    {
        $this->find("id", $id);
    }

    /**
    * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
    * Used as a callback function by usort
    */
    private static function compareActivity($first, $second)
    {
        return $first->activityCount < $second->activityCount;
    }

    public function getQuestionActivity($di)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT acronym, count(acronym) AS userQuestionCount FROM questions LEFT JOIN user ON userId = user.id GROUP BY acronym ORDER BY userQuestionCount DESC;";
        $res = $dbqb->executeFetchAll($sql);

        return $res;
    }

    public function getAnswerActivity($di)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT acronym, count(acronym) AS userAnswerCount FROM answers LEFT JOIN user ON userId = user.id GROUP BY acronym ORDER BY userAnswerCount DESC;";
        $res = $dbqb->executeFetchAll($sql);

        return $res;
    }

    public function getQCommentActivity($di)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT acronym, count(acronym) AS userQCommentCount FROM QuestionComments LEFT JOIN user ON userId = user.id GROUP BY acronym ORDER BY userQCommentCount DESC;";
        $res = $dbqb->executeFetchAll($sql);

        return $res;
    }

    public function getACommentActivity($di)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT acronym, count(acronym) AS userACommentCount FROM AnswerComments LEFT JOIN user ON userId = user.id GROUP BY acronym ORDER BY userACommentCount DESC;";
        $res = $dbqb->executeFetchAll($sql);

        return $res;
    }

    private function processQCount($qCount, $output)
    {
        foreach ($qCount as $user) {
            $userFound = false;
            foreach ($output as $out) {
                if ($user->acronym == $out->acronym) {
                    $out->userQuestionCount = $user->userQuestionCount;
                    $out->activityCount = $user->userQuestionCount;
                    $userFound = true;
                }
            }
            if ($userFound == false) {
                $user->activityCount = $user->userQuestionCount;
                array_push($output, $user);
            }
        }

        return $output;
    }

    private function processACount($aCount, $output)
    {
        foreach ($aCount as $user) {
            $userFound = false;
            foreach ($output as $out) {
                if ($user->acronym == $out->acronym) {
                    $out->userAnswerCount = $user->userAnswerCount;
                    $out->activityCount += $user->userAnswerCount;
                    $userFound = true;
                }
            }
            if ($userFound == false) {
                $user->activityCount = $user->userAnswerCount;
                array_push($output, $user);
            }
        }

        return $output;
    }

    private function processQCCount($qcCount, $output)
    {
        foreach ($qcCount as $user) {
            $userFound = false;
            foreach ($output as $out) {
                if ($user->acronym == $out->acronym) {
                    $out->userQCommentCount = $user->userQCommentCount;
                    $out->activityCount += $user->userQCommentCount;
                    $userFound = true;
                }
            }
            if ($userFound == false) {
                $user->activityCount = $user->userQCommentCount;
                array_push($output, $user);
            }
        }

        return $output;
    }

    private function processACCount($acCount, $output)
    {
        foreach ($acCount as $user) {
            $userFound = false;
            foreach ($output as $out) {
                if ($user->acronym == $out->acronym) {
                    $out->userACommentCount = $user->userACommentCount;
                    $out->activityCount += $user->userACommentCount;
                    $userFound = true;
                }
            }
            if ($userFound == false) {
                $user->activityCount = $user->userACommentCount;
                array_push($output, $user);
            }
        }
        return $output;
    }

    public function getUserActivity($di, $limit)
    {
        $qCount = $this->getQuestionActivity($di);
        $aCount = $this->getAnswerActivity($di);
        $qcCount = $this->getQCommentActivity($di);
        $acCount = $this->getACommentActivity($di);

        // Push all fields into output array to see user activity.
        $output = $qCount;

        $output = $this->processQCount($qCount, $output);
        $output = $this->processACount($aCount, $output);
        $output = $this->processQCCount($qcCount, $output);
        $output = $this->processACCount($acCount, $output);


        usort($output, array($this, 'compareActivity'));
        $topActivity = array_slice($output, 0, $limit);

        return $topActivity;
    }

    public function getQuestionStatsByUserId($di, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT sum(vote) AS rank FROM Questions INNER JOIN QuestionVotes ON Questions.id = QuestionVotes.userId WHERE Questions.userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["rank"] = $res[0]->rank;

        $sql = "SELECT count(id) AS num FROM Questions WHERE userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["num"] = $res[0]->num;

        return $stats;
    }

    public function getAnswerStatsByUserId($di, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT sum(vote) AS rank FROM Answers INNER JOIN AnswerVotes ON Answers.id = AnswerVotes.userId WHERE Answers.userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["rank"] = $res[0]->rank;

        $sql = "SELECT count(id) AS num FROM Answers WHERE userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["num"] = $res[0]->num;

        return $stats;
    }

    public function getAnswerCommentStatsByUserId($di, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT sum(vote) AS rank FROM AnswerComments INNER JOIN AnswerCommentVotes ON AnswerComments.id = AnswerCommentVotes.userId WHERE AnswerComments.userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["rank"] = $res[0]->rank;

        $sql = "SELECT count(id) AS num FROM AnswerComments WHERE userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["num"] = $res[0]->num;

        return $stats;
    }

    public function getQuestionCommentStatsByUserId($di, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT sum(vote) AS rank FROM QuestionComments INNER JOIN QuestionCommentVotes ON QuestionComments.id = QuestionCommentVotes.userId WHERE QuestionComments.userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["rank"] = $res[0]->rank;

        $sql = "SELECT count(id) AS num FROM QuestionComments WHERE userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$uid]);

        $stats["num"] = $res[0]->num;

        return $stats;
    }
}
