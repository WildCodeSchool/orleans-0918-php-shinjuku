<?php
/**
 * This file hold all routes definitions.
 *
 * PHP version 7
 *
 * @author   WCS <contact@wildcodeschool.fr>
 *
 * @link     https://github.com/WildCodeSchool/simple-mvc
 */

$routes = [
    'Article' => [ // Controller
        ['add', '/article/add', ['GET', 'POST']], // action, url, method
    ],

    'About' => [ // Controller
        ['index', '/about', 'GET'], //rajout du lien
    ],

    'Home' => [ // Controller
        ['index', '/home', 'GET'], // action, url, method
    ],

    'ProductDetails' => [ // Controller
        ['index', '/article/{id:\d+}', 'GET'], // action, url, method
    ],
];
