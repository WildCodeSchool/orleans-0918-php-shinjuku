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
    'Article' => [
        ['index', '/products', 'GET'], // action, url, method
        ['add', '/admin/add', ['GET', 'POST']], // action, url, method
        ['showAll', '/admin/list', ['GET', 'POST']], // action, url, method
        ['edit', '/admin/edit/{id}', ['GET', 'POST']], // action, url, method
        ['show', '/article/{id:\d+}', 'GET'], // action, url, method
        ['searchArticle', '/article/search', 'GET'], // action, url, method
        ['deleteArticle', '/admin/list/{id:\d+}', 'GET'],
    ],
    'About' => [ // Controller
        ['index', '/about', 'GET'], //rajout du lien
    ],
    'Home' => [ // Controller
        ['index', '/', 'GET'], // action, url, method
    ],
    'Contact' => [ // Controller
        ['send', '/contact', ['GET', 'POST']], // action, url, method
    ],
];
