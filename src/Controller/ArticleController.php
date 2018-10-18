<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\Article;
use Model\ArticleManager;

/**
 * Class ProductController
 *
 */
class ArticleController extends AbstractController
{


    /**
     * Display product listing
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        $articleManager = new ArticleManager($this->getPdo());
        $articles = $articleManager->selectAll();

        return $this->twig->render('Product/product.html.twig', ['article' => $articles]);
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

