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
    ['showMeMAnga', '/manga', 'GET'], // action, url, method
    ['showMeDvd', '/dvd', 'GET'], // action, url, method
    ['showMeGoodies', '/goodies', 'GET'], // action, url, method
    ['add', '/article/add', ['GET', 'POST']], // action, url, method
      
    ],

    'About' => [
        ['index', '/about', 'GET'], //rajout du lien
    ],


    'Home' => [ // Controller
        ['index', '/home', 'GET'], // action, url, method
    ],
];
