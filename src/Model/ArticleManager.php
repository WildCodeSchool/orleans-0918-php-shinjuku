<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace Model;

/**
 *
 */
class ArticleManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'article';

    /**
     *  Initializes this class.
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }


    /**
     * @param Article $article
     * @return int
     */
    public function insert(Article $article): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table (name, category, price, picture, description, review, highlight) VALUES (:name, :category, :price, :picture, :description, :review, :highlight )");
        $statement->bindValue('name', $article->getName(), \PDO::PARAM_STR);
        $statement->bindValue('category', $article->getCategory(), \PDO::PARAM_STR);
        $statement->bindValue('price', $article->getPrice(), \PDO::PARAM_INT);
        $statement->bindValue('picture', $article->getPicture(), \PDO::PARAM_STR);
        $statement->bindValue('description', $article->getDescription(), \PDO::PARAM_STR);
        $statement->bindValue('review', $article->getReview(), \PDO::PARAM_STR);
        $statement->bindValue('highlight', $article->getHighlight(), \PDO::PARAM_BOOL);

        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param Article $item
     * @return int
     */
    public function update(Article $item):int
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item->getId(), \PDO::PARAM_INT);
        $statement->bindValue('title', $item->getTitle(), \PDO::PARAM_STR);


        return $statement->execute();
    }
}
