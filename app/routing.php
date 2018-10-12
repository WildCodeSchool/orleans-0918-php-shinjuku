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
    'Home' => [ // Controller
        ['index', '/home', 'GET'], // action, url, method
    ],
];
