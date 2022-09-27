<?php
namespace Zardima0\Lib;

class SitemapGenerator
{
    private const MAX_FILE_SIZE = 52428800;
    private const MAX_URLS_PER_SITEMAP = 50000;
    private const MAX_URL_COUNT = 2048;

    private const ALLOWED_TYPES = ['xml','csv','json'];
    private const ALLOWED_PRI0RITY = [0.0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0,];
    private const ALLOWED_CHANGEFREQ = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never',];
    private const FILE_NAME = 'sitemap';
    private const CHANGE_FREG = [
        'always',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'never',
    ];
    private $arraySite = [];
    private $path;
    private $type;

    public function __construct(array $arrayUrl,string $type,string $path)
    {
       $start = microtime(true);
       $this->arraySite = $arrayUrl;
       $this->validateUrls($arrayUrl);
       $this->checkPath(rtrim($path));
       $this->checkType(rtrim($type));
       $this->generate();
       echo 'Время выполнения скрипта: ' . (microtime(true) - $start) . ' sec.';
    }
    private function validateUrls(array $urls)
    {
        if(sizeof($urls) >self::MAX_URLS_PER_SITEMAP) {
            throw new \Zardima0\Lib\Exception\CountUrlsException('Записать можно только 50000 ссылок,сейчас ' . sizeof($urls));
        }
        for ($i=0;$i<sizeof($urls);$i++) {
            if($urls[$i]['loc'] == null && mb_strlen($urls[$i]['loc'] <= self::MAX_URL_COUNT)) {
                throw new \Zardima0\Lib\Exception\InvalidArrayException('Длинна loc должна быть от 1 до ' . self::MAX_URL_COUNT);
            }
            if($urls[$i]['priority'] !== null && !in_array($urls[$i]['priority'],self::ALLOWED_PRI0RITY)) {
                throw new \Zardima0\Lib\Exception\InvalidArrayException('Значение priority должно быть ' . implode(',',self::ALLOWED_PRI0RITY));
            }
            if($urls[$i]['changefreq'] !== null && !in_array($urls[$i]['changefreq'],self::ALLOWED_CHANGEFREQ)) {
                throw new \Zardima0\Lib\Exception\InvalidArrayException('Значение changefreq должно быть вот таким ' . implode(',',self::CHANGE_FREG));
            }
            if($urls[$i]['lastmod'] == null) {
                throw new \Zardima0\Lib\Exception\InvalidArrayException('Значение lastmod пустое');
            }
            $this->arraySite[] = $urls[$i];
        }
    }
    private function checkType(string $type)
    {
        if(in_array(mb_strtolower($type),self::ALLOWED_TYPES)) {
            $this->type = $type;
        }else {
            throw new \Zardima0\Lib\Exception\TypeException('Неверно указан формат ' . $type  . ' ,разрешенные форматы ' . implode(',',self::ALLOWED_TYPES));
        }
    }
    private function checkPath(string $path)
    {
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
            $this->path = $path;
        }
        $this->path = $path;
    }

    private function generate()
    {
        switch ($this->type) {
            case 'xml':
                $this->generateXML();
                break;
            case 'csv':
                $this->generateCSV();
                break;
            case 'json':
                $this->generateJson();
                break;
        }
    }

    private function generateXML()
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->openURI($this->path . 'sitemap.xml');
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        for($i=0;$i<sizeof($this->arraySite);$i++) {
            $writer->startElement('url');
            $writer->writeElement('loc', htmlspecialchars($this->arraySite[$i]['loc']));
            $writer->writeElement('lastmod',$this->arraySite[$i]['lastmod']->format(DATE_ATOM));
            $writer->writeElement('changefreq', $this->arraySite[$i]['changefreq']);
            $writer->writeElement('priority',  $this->arraySite[$i]['priority']);
            $writer->endElement();
        }
        $writer->endElement();
        $writer->endDocument();
        echo $writer->outputMemory();
    }
    private function generateCSV()
    {
        $out = fopen($this->path . self::FILE_NAME . '.csv','w');
        fputcsv($out,array_keys($this->arraySite[0]));
        foreach ($this->arraySite as $item) {
            fputcsv($out,[$item['loc'],$item['lastmod'],$item['priority'],$item['changefreq']],';');
        }
        fclose($out);
    }
    private function generateJson()
    {
        file_put_contents( $this->path . self::FILE_NAME . '.json',json_encode($this->arraySite,JSON_UNESCAPED_SLASHES));
    }
}