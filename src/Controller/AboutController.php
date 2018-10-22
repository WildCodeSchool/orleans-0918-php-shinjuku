<?php

/**
 * Created by PhpStorm.
 * User: julien
 * Date: 10/10/18
 * Time: 11:30
 */
namespace Controller;
class AboutController extends AbstractController
{

    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        return $this->twig->render('Home/about.html.twig');
    }

}
