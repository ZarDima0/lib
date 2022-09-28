<h1>Генератор Sitemap</h1>
<h2>О библиотеке</h2>
<p>
Будет создаваться sitemap в той директории которую указать, если там уже есть файл с именем sitemap, то он его перезапишет.
</p>
<p>
Ограничение 50 тысяч ссылок.
</p>

<h2>Как запустить</h2>

````
composer require zardima0/lib
````

````
//index.php

require_once $_SERVER['DOCUMENT_ROOT'] . './vendor/autoload.php';
use Zardima0\Lib\SitemapGenerator;

//Первый параметр принимает массив вида 
$array = [
    [
        'loc' => 'https://site.ru/',
        'lastmod' => '2020-12-14',
        'priority' => 1,
        'changefreq' => 'hourly',
    ],
];

// Типы которые можно создать xml,csv, json;
$type = 'xml';

//Путь куда нужно сохранить, если такой директории нет, то она создается;
$path = __DIR__ . '/upload/';

new SitemapGenerator($array, $type, $path);
````
<h2>Запускается в консоле</h2>

````
php index.php
````