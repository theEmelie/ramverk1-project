<?php

namespace Anax\Questions;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class Questions extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Questions";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $title;
    public $text;
    public $created;
    public $updated;
    public $deleted;
    public $active;


    public function getAllQuestions()
    {
        return $this->findAll();
    }

    public function getQuestionById($qid)
    {
        return $this->find("id", $qid);
    }

    public function getAllQuestionsByUserId($uid)
    {
        return $this->findAllWhere("userId = ?", $uid);
    }

    public function getQuestionTitleByAnswerId($di, $aid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT Questions.title AS questionTitle, Questions.id AS questionId FROM Answers INNER JOIN Questions ON Questions.id = Answers.questionId WHERE Answers.id = ?;";
        $res = $dbqb->executeFetchAll($sql, [$aid]);

        return $res[0];
    }

    /**
    * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
    */
    private static function sortByDateDESC($first, $second)
    {
        return $first->updated < $second->updated;
    }

    public function getAllQuestionsByDateDesc($limit)
    {
        $questions = $this->findAll();
        usort($questions, array($this, 'sortByDateDESC'));
        $slicedQuestions = array_slice($questions, 0, $limit);

        return $slicedQuestions;
    }

    public function getNumberOfAnswers($di, $qid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM Answers WHERE questionId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qid]);

        return count($res);
    }

    public function getVoteCount($di, $qid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT SUM(vote) AS voteCount FROM QuestionVotes WHERE questionId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qid]);

        if ($res[0]->voteCount == null) {
            $res[0]->voteCount = 0;
        }

        return $res[0]->voteCount;
    }
}
