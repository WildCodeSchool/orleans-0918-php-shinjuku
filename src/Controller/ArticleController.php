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

    public function showMeMAnga()
    {
        $articleManager=new ArticleManager($this->getPdo());
        $articles = $articleManager->selectArticlesByCategory('Manga');

        return $this->twig->render('Product/manga.html.twig', ['article' => $articles]);
    }

    public function showMeDvd()
    {
        $articleManager=new ArticleManager($this->getPdo());
        $articles = $articleManager->selectArticlesByCategory('DVD');

        return $this->twig->render('Product/dvd.html.twig', ['article' => $articles]);
    }

    public function showMeGoodies()
    {
        $articleManager=new ArticleManager($this->getPdo());
        $articles = $articleManager->selectArticlesByCategory('Goodies');

        return $this->twig->render('Product/goodies.html.twig', ['article' => $articles]);
    }
}
