<?php
require('fpdf.php');
require_once('fpdi/autoload.php');
use setasign\Fpdi;

include('phpqrcode.php');
$json = urldecode ($_GET["data"]);
$array = explode(",",$json);
$color = urldecode ($_GET["color"]);
$naam = urldecode ($_GET["name"]);

$content = "";
$coverImages = "";

foreach($array as $item) {
    $loadedDom = new DOMDocument('1.0');
    @$loadedDom->loadHTMLFile($item);
	$xpath = new DOMXPath($loadedDom);
    $image = $loadedDom->getElementsByTagName('img')[4];

     $paragrafen = $loadedDom->getElementsByTagName('p');
     $paragraaf = $paragrafen[rand(0,$paragrafen->length)];
     $lines = explode(',', $paragraaf->nodeValue);
     //$lines = explode(',', $lines1[0]);
     $newQuote = $loadedDom->createElement("h3", $lines[0].'.');

		 if(strlen( $lines[0]) > 30){
			 $class = 'lang';
		 }else{
			 $class = 'kort';
		 }

     $newQuote->setAttribute('class', 'quote '.$class);
     $paragraaf->parentNode->insertBefore($newQuote, $paragraaf);

    foreach($loadedDom->getElementsByTagName('iframe') as $iframe) {
    	$iframe->parentNode->removeChild($iframe);
    }

	foreach($loadedDom->getElementsByTagName('h1') as $header) {
	    	if($header->nodeValue == "Footnotes"){
          $header->setAttribute('class', 'footnotes');
	    		$nextelement = $xpath->query("following-sibling::*[1]", $header);
					$nextelement[0]->setAttribute('class', 'footnotes');
	    	}
				if($header->nodeValue == "Bibliography"){
          $header->setAttribute('class', 'bibliography');
	    		$nextelement = $xpath->query("following-sibling::*[1]", $header);
					$nextelement[0]->setAttribute('class', 'bibliography');
	    	}
	    }


    foreach($loadedDom->getElementsByTagName('a') as $anchor) {
        $link = $anchor->getAttribute('href');
        if (substr( $link, 0, 1 ) !== "#") {
            $newImg = $loadedDom->createElement("img");
            $newImg->setAttribute('src', 'data:image/png;base64,'.qr($link));
            $newImg->setAttribute('class', 'qr');
            $parent = $anchor->parentNode;
            if($parent->getAttribute('class') !== 'article__tags'){
                $parent->insertBefore($newImg, $anchor);
            }
        }

    }

    $artikel = $loadedDom->getElementsByTagName('article')[0];
    $content .= $artikel->ownerDocument->saveHTML( $artikel );



    $coverImages .= "<div style='left:".rand(-30,100)."%;top:".rand(-30,100)."%;width:".rand(30,100)."%;'>".$image->ownerDocument->saveHTML( $image )."</div>";
}

$fileContents = file_get_contents('preprint.css');
$fileContents = str_replace("KLEUR",$color,$fileContents);
$fileContents = str_replace("RAND1",rand(5,40),$fileContents);
$fileContents = str_replace("RAND2",rand(5,40),$fileContents);
$fileContents = str_replace("RAND3",rand(5,40),$fileContents);
$fileContents = str_replace("RAND4",rand(5,40),$fileContents);
file_put_contents('print.css', $fileContents);

ob_start();

echo "
<html>
    <head>
    <link rel='stylesheet' href='print.css'>
    <title>".$naam."</title>
    </head>
    <body>
    <div id='cover'>"
    ."<h1>".$naam."</h1>"
. $coverImages
    ."</div>
    "
. $content
."</body></html>"
    ;


$object = ob_get_contents();
ob_end_clean();
file_put_contents("cache.html",$object);
exec('weasyprint cache.html temp.pdf');

$pdf = new Fpdi\Fpdi('L');

$pw = 148;
$ph = 210;

$pagecount = $pdf->setSourceFile('temp.pdf');
$pp = GetBookletPages($pagecount);

$counter = 0;
foreach ($pp as $v) {

    $counter++;

    if($counter % 2 == 0 ){
        $degrees = 180;
    }else{
        $degrees = 0;
    }

    $pdf->AddPage('L', '', $degrees);

    if ($v[0] > 0 && $v[0] <= $pagecount) {
        $tplIdx = $pdf->importPage($v[0]);
        $pdf->useTemplate($tplIdx, 0, 0, $pw, $ph);
    }

    if ($v[1] > 0 && $v[1] <= $pagecount) {
        $tplIdx = $pdf->importPage($v[1]);
        $pdf->useTemplate($tplIdx, $pw, 0, $pw, $ph);
    }
}

$pdf->SetTitle($naam);
$pdf->Output();

exit;

function GetBookletPages($np) {
    $np = 4 * ceil($np / 4);
    $pp = array();

    for ($i = 1; $i <= $np / 2; $i++) {
        $p1 = $np - $i + 1;
        $pp[] = ($i % 2 == 1)  ? array( $p1,  $i ) : array( $i, $p1 );
    }

    return $pp;
}

function qr($url){
    ob_start();
    QRCode::png($url, null);
    $imageString = base64_encode( ob_get_contents() );
    ob_end_clean();
    return $imageString;
}
 ?>
