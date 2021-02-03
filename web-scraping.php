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
$content = $crawler->filter('#respostaWS')->html();

//Return grocery store name
$name = $crawler->filter('.NFCCabecalho_SubTitulo')->text();

//Return grocery store address
$address = $crawler->filter('.NFCCabecalho_SubTitulo1')->eq(1)->text();

//Return date and time
$datetime = $crawler->filter('.NFCCabecalho_SubTitulo')->eq(2)->text();

echo $name . "\n";
echo $address . "\n";
echo $datetime . "\n";