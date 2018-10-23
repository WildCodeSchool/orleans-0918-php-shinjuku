<?php
/**
 * Created by PhpStorm.
 * User: wilder15
 * Date: 19/10/18
 * Time: 11:45
 */

namespace Model;


class HomeManager extends AbstractManager
{

    const TABLE = 'article';

    /**
     *  Initializes this class.
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }



    public function insert(Item $item): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table (`title`) VALUES (:title)");
        $statement->bindValue('title', $item->getTitle(), \PDO::PARAM_STR);


        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
    }



    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }



    public function update(Item $item):int
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item->getId(), \PDO::PARAM_INT);
        $statement->bindValue('title', $item->getTitle(), \PDO::PARAM_STR);


        return $statement->execute();
    }

    public function selectHighlight()
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE highlight IS NOT NULL ORDER BY category DESC ");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('highlight', $highlight, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchall();
    }
}
