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
    const ARTICLE_BY_PAGE=16;
    /**
     * ArticleManager constructor.
     * @param \PDO $pdo
     *  Initializes this class.
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }
    /*
    *searching article by category and by name(when searching by the client
    */
    public function searchArticle(int $currentPage, ?string $category = '', ?string $search = ''): array
    {
        $queryFragments = [];

        if (!empty($search)) {
            $queryFragments[] = "name LIKE :search";
        }
        if (!empty($category)) {
            $queryFragments[] = "category =:category";
        }
        $offset=($currentPage*self::ARTICLE_BY_PAGE)-self::ARTICLE_BY_PAGE;
        $statement = $this->pdo->prepare('SELECT * FROM ' . $this->table . " WHERE " .
            implode(" AND ", $queryFragments) . " LIMIT ".self::ARTICLE_BY_PAGE." OFFSET " .$offset);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        if (!empty($search)) {
            $statement->bindValue('search', "%$search%", \PDO::PARAM_STR);
        }
        if (!empty($category)) {
            $statement->bindValue('category', $category, \PDO::PARAM_STR);
        }

        if ($statement->execute()) {
            return $statement->fetchAll();
        }
    }
    /**
     * @param string $category
     * @param string $search
     * @return int
     */
    public function countArticle(?string $category, ?string $search): int
    {
        $queryFragments = [];

        if (!empty($search)) {
            $queryFragments[] = "name LIKE :search";
        }
        if (!empty($category)) {
            $queryFragments[] = "category =:category";
        }
        $statement= $this->pdo->prepare('SELECT COUNT(*) FROM ' . $this->table . " WHERE " .
            implode(" AND ", $queryFragments));
        $statement->setFetchMode(\PDO::FETCH_COLUMN, 0);
        if (!empty($search)) {
            $statement->bindValue('search', "%$search%", \PDO::PARAM_STR);
        }
        if (!empty($category)) {
            $statement->bindValue('category', $category, \PDO::PARAM_STR);
        }
        if ($statement->execute()) {
            return $statement->fetchColumn();
        }
    }
    /**
     * @param Article $article
     * @return int
     */
    public function insert(Article $article): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
        (name, category, price, picture, description, review, highlight) 
        VALUES (:name, :category, :price, :picture, :description, :review, :highlight )");
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
    /**
     * @param Article $article
     * @return int
     */
    public function edit(Article $article): int
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table 
            SET id=:id,name=:name, category=:category, price=:price, picture=:picture, 
        description=:description, review=:review, highlight=:highlight 
            WHERE id=:id");
        $statement->bindValue('id', $article->getId(), \PDO::PARAM_INT);
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
        return $this->pdo->query("SELECT * FROM $this->table
        WHERE highlight IS NOT NULL ORDER BY category DESC ", \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }
    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
