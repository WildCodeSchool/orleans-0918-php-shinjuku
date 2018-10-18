<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 17/10/18
 * Time: 11:28
 */

namespace Controller;

use Model\ArticleManager;


class ArticleController extends AbstractController
{
    public function show(int $id) {
        $articleManager = new ArticleManager($this->pdo);
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Article/article_details.html.twig', ['Article' => $article]);
    }
}
