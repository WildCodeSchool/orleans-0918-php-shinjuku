<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\Home;
use Model\HomeManager;


class HomeController extends AbstractController
{



    public function article()
    {
        $articlesManager = new HomeManager($this->getPdo());
        $articles = $articlesManager->selecthighlight();
        return $this->twig->render('Home/home.html.twig', ['article' => $articles]);
    }


}
