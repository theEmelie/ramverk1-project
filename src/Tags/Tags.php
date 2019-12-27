<?php

namespace Anax\Tags;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class Tags extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tags";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $tag;


    public function tagExists($tagName)
    {
        $this->find("tag", $tagName);
        return $this->id > 0;
    }

    public function getTagById($tagId)
    {
        $tagObj = $this->find("id", $tagId);
        return $tagObj;
    }

    public function getTagByName($tagName)
    {
        return $this->find("tag", $tagName);
    }

    public function getAllTags()
    {
        return $this->findAll();
    }

    public function getMostPopularTags($di, $limit)
    {
        $dbqb = $di->get("dbqb");
        $dbqb->connect();
        $sql = "SELECT tags.id, tags.tag, count(tags.tag) as TagCount from TagsQuestions LEFT JOIN tags ON tagId = tags.id GROUP BY tags.tag ORDER BY TagCount DESC LIMIT ?;";
        $res = $dbqb->executeFetchAll($sql, [$limit]);

        return $res;
    }
}
