<?php

namespace Anax\AnswerCommentVotes;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class AnswerCommentVotes extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "AnswerCommentVotes";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $acId;
    public $vote;

    private function addVote($di, $acid, $uid, $vote)
    {
        $acObj = new AnswerCommentVotes();
        $acObj->setDb($di->get("dbqb"));
        $acObj->acId = $acid;
        $acObj->userId = $uid;
        $acObj->vote = $vote;
        $acObj->save();
    }

    private function updateVote($di, $id, $vote)
    {
        $acObj = new AnswerCommentVotes();
        $acObj->setDb($di->get("dbqb"));
        $acObj->find("id", $id);
        $acObj->vote = $vote;
        $acObj->save();
    }

    public function upVoteAnswerComment($di, $acid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM AnswerCommentVotes WHERE acId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$acid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $acid, $uid, 1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, 1);
        }

        return;
    }

    public function downVoteAnswerComment($di, $acid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM AnswerCommentVotes WHERE acId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$acid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $acid, $uid, -1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, -1);
        }

        return;
    }

    public function countVoteForACid($di, $acid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT SUM(vote) AS voteCount FROM AnswerCommentVotes WHERE acId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$acid]);

        return $res[0];
    }
}
