<?php

namespace Anax\TagsQuestions;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class TagsQuestions extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "TagsQuestions";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $tagId;
    public $questionId;

    public function getAllByQuestionId($qid)
    {
        return $this->findAllWhere("questionId = ?", $qid);
    }

    public function getAllByTagId($tid)
    {
        return $this->findAllWhere("tagId = ?", $tid);
    }
}
