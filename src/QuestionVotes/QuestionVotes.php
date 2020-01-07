<?php

namespace Anax\QuestionVotes;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class QuestionVotes extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "QuestionVotes";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $questionId;
    public $vote;

    private function addVote($di, $qid, $uid, $vote)
    {
        $qvObj = new QuestionVotes();
        $qvObj->setDb($di->get("dbqb"));
        $qvObj->questionId = $qid;
        $qvObj->userId = $uid;
        $qvObj->vote = $vote;
        $qvObj->save();
    }

    private function updateVote($di, $id, $vote)
    {
        $qvObj = new QuestionVotes();
        $qvObj->setDb($di->get("dbqb"));
        $qvObj->find("id", $id);
        $qvObj->vote = $vote;
        $qvObj->save();
    }

    public function upVoteQuestion($di, $qid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM QuestionVotes WHERE questionId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $qid, $uid, 1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, 1);
        }

        return;
    }

    public function downVoteQuestion($di, $qid, $uid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT * FROM QuestionVotes WHERE questionId = ? AND userId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qid, $uid]);

        if (count($res) == 0) {
            // User hasnt voted on this question
            $this->addVote($di, $qid, $uid, -1);
        } else {
            // User has voted, so change/set vote
            $this->updateVote($di, $res[0]->id, -1);
        }

        return;
    }

    public function countVoteForQid($di, $qid)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT SUM(vote) AS voteCount FROM QuestionVotes WHERE questionId = ?;";
        $res = $dbqb->executeFetchAll($sql, [$qid]);

        return $res[0];
    }
}
