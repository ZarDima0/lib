<?php
namespace Zardima0\Lib;
class SitemapGenerator
{
    private $arraySite = [];
    private $path;
    private $type;
    const ALLOWED_TYPES = ['xml','scv','json'];

    public function __construct(array $arrayUrl,string $type,string $path)
    {
        $this->arraySite = $arrayUrl;
        $this->checkPath($path);
        $this->checkType($type);
        $this->generateJson();
        $this->generateCSV();
        $this->generateXML();
    }

    private function checkType(string $type)
    {
        if(in_array(mb_strtolower($type),self::ALLOWED_TYPES)) {
            echo 'Значение есть';
            return false;
        }else {
            echo 'значение нет';
            return true;
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
    public function generate()
    {
        var_dump($this->path);
        file_put_contents($this->path . 'sipe.txt','Dima');
    }
    private function generateXML()
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->openURI($this->path . 'sitemap.xml');
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        for($i=0;$i<count($this->arraySite);$i++) {
            $writer->startElement('url');
            $writer->writeElement('loc',htmlspecialchars($this->arraySite[$i]['loc']));
            $writer->writeElement('lastmod', $this->arraySite[$i]['lastmod']);
            $writer->writeElement('changefreq',$this->arraySite[$i]['changefreq']);
            $writer->writeElement('priority', $this->arraySite[$i]['priority']);
            $writer->endElement();
        }
        $writer->endElement();
        $writer->endDocument();
        echo $writer->outputMemory();
    }
    private function generateCSV()
    {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen($this->path . 'sitemap.csv','w');
        fputcsv($out,array_keys($this->arraySite[0]));
        foreach ($this->arraySite as $item) {
            var_dump($item['loc']);
            fputcsv($out,[$item['loc'],$item['lastmod'],$item['priority'],$item['changefreq']],';');
        }
        fclose($out);
    }
    private function generateJson()
    {
        file_put_contents( $this->path . 'sitemap.json',json_encode($this->arraySite,JSON_UNESCAPED_SLASHES));
    }
}