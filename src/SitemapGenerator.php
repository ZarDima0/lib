<?php
namespace Zardima0\Lib;
class SitemapGenerator
{
    private $arraySite = [];
    private $path;
    private $type;

    public function __construct(array $arrayUrl,string $type,string $path)
    {
        $this->arraySite = $arrayUrl;
        $this->checkPath($path);
        $this->type = $type;
        $this->generate();
    }

    private function checkPath(string $path)
    {

    }
    public function generate()
    {

    }
    private function generateXML()
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->openURI('upload/sitemap.xml');
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
        $out = fopen('sitemap.csv','w');
        fputcsv($out,array_keys($this->arraySite[0]));
        foreach ($this->arraySite as $item) {
            var_dump($item['loc']);
            fputcsv($out,[$item['loc'],$item['lastmod'],$item['priority'],$item['changefreq']],';');
        }
        fclose($out);
    }
    private function generateJson()
    {
        file_put_contents('sitemap.json',json_encode($this->arraySite,JSON_UNESCAPED_SLASHES));
    }
}