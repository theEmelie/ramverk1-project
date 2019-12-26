<?php

namespace Anax\QComments;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

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
}
