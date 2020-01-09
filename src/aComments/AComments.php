<?php

namespace Anax\AComments;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Anax\User\User;
use Anax\Textfilter\MyTextfilter;
use Anax\AnswerCommentVotes\AnswerCommentVotes;

/**
 * A database driven model.
 */
class AComments extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "AnswerComments";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $answerId;
    public $text;
    public $created;
    public $updated;
    public $deleted;
    public $active;

    public function getAllaCommentsByAnswerId($aid)
    {
        return $this->findAllWhere("answerId = ?", $aid);
    }

    public function getAllaCommentsByUserId($uid)
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

    public function getAcomment($di, $aid)
    {
        // Find all comments for answer
        $aComments = $this->getAllaCommentsByAnswerId($aid);

        foreach ($aComments as $com) {
            $comUser = new User();
            $comUser->setDb($di->get("dbqb"));
            $res = $comUser->getUserDataById($com->userId);

            $com->username = $comUser->acronym;

            // Run markdown on answer comments text
            $com->text = $this->runMarkdown($com->text);

            $answerCommentVotes = new AnswerCommentVotes();
            $answerCommentVotes->setDb($di->get("dbqb"));
            $res = $answerCommentVotes->countVoteForACid($di, $com->id);

            if ($res->voteCount == null) {
                $res->voteCount = 0;
            }

            $com->voteCount = $res->voteCount;
        }

        return $aComments;
    }
}
