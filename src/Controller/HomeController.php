<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12/10/18
 * Time: 09:36
 */
namespace Controller;
use Model\Home;
use Model\HomeManager;
use Model\ArticleManager;
class HomeController extends AbstractController
{

    public function index()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectHighlight();
        return $this->twig->render('Home/home.html.twig', ['article' => $articles]);
    }
    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $articleManager->delete($id);
        header('Location:/');
    }
}