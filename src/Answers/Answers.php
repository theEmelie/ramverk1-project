<?php

namespace Anax\Answers;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class Answers extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Answers";

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

    public function getAllAnswersByQuestionId($qid)
    {
        return $this->findAllWhere("questionId = ?", $qid);
    }

    public function getAllAnswersByUserId($uid)
    {
        return $this->findAllWhere("userId = ?", $uid);
    }
}
