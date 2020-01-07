<?php

namespace Anax\AnswerVotes;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class AnswerVotes extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "AnswerVotes";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $answerId;
    public $vote;

    private function addVote($di, $aid, $uid, $vote)
    {
        $avObj = new AnswerVotes();
        $avObj->setDb($di->get("dbqb"));
        $avObj->answerId = $aid;
        $avObj->userId = $uid;
        $avObj->vote = $vote;
        $avObj->save();
    }

    private function updateVote($di, $id, $vote)
    {
        $avObj = new AnswerVotes();
        $avObj->setDb($di->get("dbqb"));
        $avObj->find("id", $id);
        $avObj->vote = $vote;
        $avObj->save();
    }

    public function upVoteAnswer($di, $aid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM AnswerVotes WHERE answerId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$aid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $aid, $uid, 1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, 1);
        }

        return;
    }

    public function downVoteAnswer($di, $aid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM AnswerVotes WHERE answerId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$aid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $aid, $uid, -1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, -1);
        }

        return;
    }

    public function countVoteForAid($di, $aid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT SUM(vote) AS voteCount FROM AnswerVotes WHERE answerId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$aid]);

        return $res[0];
    }
}
