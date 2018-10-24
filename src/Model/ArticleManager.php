<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */
namespace Model;
class ArticleManager extends AbstractManager
{
    const TABLE = 'article';
    /**
     * ArticleManager constructor.
     * @param \PDO $pdo
     *  Initializes this class.
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }
    /**
     *searching article by category and by name(when searching by the client
     */
    public function searchArticle(string $category,string $search=''): array
    {
        $searching = '';
        if (!empty($search)) {
            $searching = "AND name LIKE '%$search%'";
        }
        return $this->pdo->query('SELECT * FROM ' . $this->table . " WHERE   category ='$category' $searching", \PDO::FETCH_CLASS, $this->className)->fetchAll();
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
        $statement->bindValue('price', $article->getPrice(), \PDO::PARAM_STR);
        $statement->bindValue('picture', $article->getPicture(), \PDO::PARAM_STR);
        $statement->bindValue('description', $article->getDescription(), \PDO::PARAM_STR);
        $statement->bindValue('review', $article->getReview(), \PDO::PARAM_STR);
        $statement->bindValue('highlight', $article->getHighlight(), \PDO::PARAM_BOOL);
        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
    }

    public function selectHighlight()
    {
        return $this->pdo->query("SELECT * FROM $this->table WHERE highlight IS NOT NULL ORDER BY category DESC ", \PDO::FETCH_CLASS, $this->className)->fetchAll();
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

}
