<?php
//declare(strict_types = 1);
//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . './vendor/autoload.php';
$array = [
    [
        'loc'=> 'https://site.ru/',
        'lastmod' => '2020-12-14',
        'priority'=> null,
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
$url = [

];
for($i=0;$i<10;$i++) {
    $url[] =
        [
            'loc'=> '/',
            'lastmod' => '2020-12-14',
            'priority'=> 1,
            'changefreq' => 'hourly',
        ];
}
use Zardima0\Lib\SitemapGenerator;
$path = __DIR__ . '/upload/ ';
new SitemapGenerator($url,'json ',$path);

