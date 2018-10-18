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
    'Article' =>[
    ['index', '/products', 'GET'], // action, url, method
    ['listByCategory', '/article/{category}', 'GET'], // action, url, method
    ],
    'Home' => [ // Controller
        ['index', '/home', 'GET'], // action, url, method
    ],
];
