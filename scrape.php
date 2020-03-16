<?php
$href = urldecode ($_GET["href"]);

$tags = array();
function crawl_page($url, $depth)
{
    global $tags;

    if ($depth === 0) {
        return;
    }

    $dom = new DOMDocument('1.0');
    @$dom->loadHTMLFile($url);
    $xpath = new DOMXpath($dom);

    $anchors = $dom->getElementsByTagName('a');
    $headers = $dom->getElementsByTagName('h1');
    $nodes = $xpath->query('//text()');

    $textNodeContent = "";
    foreach($nodes as $node) {
        $textNodeContent .= " $node->nodeValue";
    }

    foreach ($anchors as $element) {

        if($element->parentNode->getAttribute('class') == "article__tags"){
            $title = $headers->item(0)->nodeValue;
            if(!array_key_exists($element->nodeValue, $tags)){
                $tags[$element->nodeValue] = array();
            }
            if(!in_array ( array($title, $url, str_word_count( $textNodeContent, 0 )), $tags[$element->nodeValue])){
                array_push($tags[$element->nodeValue], array($title, $url, str_word_count( $textNodeContent, 0 )));
            }
        }

        $href = $element->getAttribute('href');
        crawl_page($href, $depth - 1);
    }

}
crawl_page($href , 3);

foreach ($tags as $tagname=>$tagarray) {?>
<details>
<summary><?=$tagname?></summary>
<div id="details-<?=$tagname?>">
<?php foreach ($tagarray as &$article) {?>
<p data-url="<?=$article[1]?>" data-tag="<?=$tagname?>" data-words="<?=$article[2]?>" draggable="true" ondragend="dragEnd()" ondragover="dragOver(event)" ondragstart="dragStart(event)" id=drag-'<?=$article[0]?>'><?=$article[0]?> <a target="_blank" href="<?=$article[1]?>" title="view article"> ≣</a></p>
<?php  } ?>
</div>
</details>
<?php  } ?>
