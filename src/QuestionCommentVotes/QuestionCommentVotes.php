<?php

namespace Anax\QuestionCommentVotes;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class QuestionCommentVotes extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "QuestionCommentVotes";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $qcId;
    public $vote;

    private function addVote($di, $qcid, $uid, $vote)
    {
        $qcObj = new QuestionCommentVotes();
        $qcObj->setDb($di->get("dbqb"));
        $qcObj->qcId = $qcid;
        $qcObj->userId = $uid;
        $qcObj->vote = $vote;
        $qcObj->save();
    }

    private function updateVote($di, $id, $vote)
    {
        $qcObj = new QuestionCommentVotes();
        $qcObj->setDb($di->get("dbqb"));
        $qcObj->find("id", $id);
        $qcObj->vote = $vote;
        $qcObj->save();
    }

    public function upVoteQuestionComment($di, $qcid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM QuestionCommentVotes WHERE qcId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qcid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $qcid, $uid, 1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, 1);
        }

        return;
    }

    public function downVoteQuestionComment($di, $qcid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM QuestionCommentVotes WHERE qcId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qcid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $qcid, $uid, -1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, -1);
        }

        return;
    }

    public function countVoteForQCid($di, $qcid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT SUM(vote) AS voteCount FROM QuestionCommentVotes WHERE qcId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qcid]);

        return $res[0];
    }
}
