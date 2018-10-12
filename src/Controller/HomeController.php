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

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('home.html.twig');
    }
}