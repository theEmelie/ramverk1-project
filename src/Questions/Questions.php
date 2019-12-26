<?php

namespace Anax\Questions;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class Questions extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Questions";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $userId;
    public $title;
    public $text;
    public $created;
    public $updated;
    public $deleted;
    public $active;


    public function getAllQuestions()
    {
        return $this->findAll();
    }

    public function getQuestionById($qid)
    {
        return $this->find("id", $qid);
    }

    public function getAllQuestionsByUserId($uid)
    {
        return $this->findAllWhere("userId = ?", $uid);
    }
}