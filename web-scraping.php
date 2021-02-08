<?php
require 'vendor/autoload.php';

use Curl\Curl;
use Symfony\Component\DomCrawler\Crawler;

$url            = 'https://www.sefaz.rs.gov.br/ASP/AAE_ROOT/NFE/SAT-WEB-NFE-NFC_QRCODE_1.asp?p=';
$url_parameter  = '43200393015006002590651020008821821466194728%7C2%7C1%7C1%7CAD54C40F4AEE1A2EB5834E35B3CB57EC0BC43E80';

$curl = new Curl();
$curl->get($url . $url_parameter);
$html = $curl->response;

$crawler = new Crawler($html);
//$content = $crawler->filter('#respostaWS')->html();

$datetime = $crawler->filter('.NFCCabecalho_SubTitulo')->eq(2)->text();
$datetime = array_slice(explode(' ', $datetime), -2);

$date = $datetime[0];
$date = explode('/', $date);
$date = $date[2] . '-' . $date[0] . '-' . $date[1];
$time = $datetime[1];
$datetime = $date . ' ' . $time;

$data = array(
    'name'      => $crawler->filter('.NFCCabecalho_SubTitulo')->text(),
    'address'   => $crawler->filter('.NFCCabecalho_SubTitulo1')->eq(1)->text(),
    'datetime'  => $datetime,
);


foreach($data as $key => $value)
    echo "$key:\t$value.\n";

$id_items = $crawler->filterXPath('//tr[contains(@id, "Item + ")]')->evaluate('substring-after(@id, "+ ")');
//var_dump($id_items);

$items = array();

foreach($id_items as $key){
    $items["item-$key"] = $crawler->filterXPath('//*[@id="Item + ' . $key. '"]')->each(function (Crawler $node, $i){
        return $node->text();
    });
}

var_dump($items);