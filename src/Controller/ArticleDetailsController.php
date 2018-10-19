<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12/10/18
 * Time: 09:36
 */

namespace Controller;

class ArticleDetailsController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Article/article_details.html.twig');
    }
}
