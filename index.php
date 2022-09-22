<?php
declare(strict_types = 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . './vendor/autoload.php';

var_dump(__DIR__);
$array = [
    [
        'loc'=> 'https://site.ru/',
        'lastmod' => '2020-12-14',
        'priority'=> 1,
        'changefreq' => 'hourly',
    ],
    [
        'loc'=> 'https://site.ru/',
        'lastmod' => '2020-12-14',
        'priority'=> 1,
        'changefreq' => 'hourly',
    ],
    [
        'loc'=> 'https://site.ru/',
        'lastmod' => '2020-12-14',
        'priority'=> 1,
        'changefreq' => 'hourly',
    ]
];

use Zardima0\Lib\SitemapGenerator;
$path = __DIR__ . '/upload/sitemap.xml';
var_dump($path);
$newSitemap = new SitemapGenerator($array,'xml',$path);

