<?php

namespace Anax\QComments;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Anax\User\User;
use Anax\Textfilter\MyTextfilter;
use Anax\QuestionCommentVotes\QuestionCommentVotes;

/**
 * A database driven model.
 */
class QComments extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "QuestionComments";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $questionId;
    public $text;
    public $created;
    public $updated;
    public $deleted;
    public $active;

    public function getAllqCommentsByQuestionId($qid)
    {
        return $this->findAllWhere("questionId = ?", $qid);
    }

    public function getAllqCommentsByUserId($uid)
    {
        return $this->findAllWhere("userId = ?", $uid);
    }

    /**
    * Function to run markdown textfilter on text
    *  @return string
    */
    public function runMarkdown($text)
    {
        $myTextFilter = new MyTextfilter();

        $text = $myTextFilter->parse($text, "markdown");
        return $text;
    }

    public function getQComment($di, $qid)
    {
        // Find all comments
        $qComments = $this->getAllqCommentsByQuestionId($qid);

        foreach ($qComments as $com) {
            $comUser = new User();
            $comUser->setDb($di->get("dbqb"));
            $res = $comUser->getUserDataById($com->userId);

            $com->username = $comUser->acronym;

            // Run markdown on question comments text
            $com->text = $this->runMarkdown($com->text);

            $questionCommentVotes = new QuestionCommentVotes();
            $questionCommentVotes->setDb($di->get("dbqb"));
            $res = $questionCommentVotes->countVoteForQCid($di, $com->id);

            if ($res->voteCount == null) {
                $res->voteCount = 0;
            }

            $com->voteCount = $res->voteCount;
        }

        return $qComments;
    }
}
