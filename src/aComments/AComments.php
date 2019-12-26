<?php

namespace Anax\AComments;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

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
}
