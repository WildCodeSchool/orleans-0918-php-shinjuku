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
    
      public function searchArticle(string $category,string $search=''): array
    {
        $searching = '';
        if(!empty($search)) {
            $searching = "AND name LIKE '%$search%'";
        }
        return $this->pdo->query('SELECT * FROM ' . $this->table . " WHERE   category ='$category' $searching", \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }
}
