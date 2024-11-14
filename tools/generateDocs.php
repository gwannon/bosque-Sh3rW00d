<?php

require __DIR__ . '/../vendor/autoload.php';

include(__DIR__ ."/config.php");

//Generamos el HTML
use FastVolt\Helper\Markdown;
$mkd = Markdown::new();
$mkd->setContent(file_get_contents(__DIR__ . "/../bosque-Sh3rW00d.md"));
$tags['HTML'] = $mkd->toHtml();
$html = file_get_contents(__DIR__ . "/template.html");
foreach ($tags as $tag => $value) {
  $html = str_replace("|".$tag."|", $value, $html); 
}
$html = str_replace("<hr />", "</div><div class=\"saltopagina\"></div>\n</section>\n<section>", $html); 
$html = str_replace("<p>\saltopagina</p>", "</div><div class=\"saltopagina\"></div><div class='columns'>", $html);
$html = str_replace("<p>\saltocolumna</p>", "<p class=\"saltocolumna\"></p>", $html);
$html = str_replace("<p>\sincolumna</p>", "</div>", $html);
$html = str_replace("<p>\concolumna</p>", "<div class='columns'>", $html);
$html = str_replace("</h1>", "</h1>\n<div class=\"saltopagina\"></div><div class='columns'>", $html); 
$html = str_replace("<div class='columns'>\n</div>", "", $html); 
$html = str_replace("<table>", "<div><table>", $html); 
$html = str_replace("</table>", "</table></div>", $html); 

$html = str_replace("<p><strong>Semilla de aventura:</strong>", "<p class='seed'><strong>Semilla de aventura:</strong>", $html); 


file_put_contents(__DIR__ . "/../index.html", $html);

echo "InfoKey: Subject\n";
echo "InfoValue: ".$tags['DESCRIPTION']." Versión ".$tags['VERSION']."\n\n";
echo "InfoKey: Author\n";
echo "InfoValue: ".$tags['AUTHOR']."\n\n";
echo "InfoKey: Keywords\n";
echo "InfoValue: ".$tags['KEYWORDS']."\n\n";



/* Generamos indice */
/* -------------------------------------------------------------- */
$doc = new DOMDocument();
$internalErrors = libxml_use_internal_errors(true);
$doc->loadHTMLFile("index.html");
$body = $doc->getElementsByTagName('body');
$body = $body->item(0);
$json = [];

$html = explode("\n", removeHtmlComments($doc->savehtml($body)));

$lines = [];
foreach($html as $line) {
  $line = cleanLine($line);
  if(preg_match("/(<h1>|<h2|<h3|<h4|saltopagina)/", $line)) $lines[] = $line;
}
$counter = 1;
echo "BookmarkBegin\n";
echo "BookmarkTitle: Portada\n";
echo "BookmarkLevel: 1\n";
echo "BookmarkPageNumber: {$counter}\n";
$json[] = ["title" => "Portada","page" => $counter];

$counter = 3;
echo "BookmarkBegin\n";
echo "BookmarkTitle: Licencia de uso\n";
echo "BookmarkLevel: 1\n";
echo "BookmarkPageNumber: {$counter}\n";
$json[] = ["title" => "Licencia de uso","page" => $counter];

$counter = 4;
echo "BookmarkBegin\n";
echo "BookmarkTitle: Índice\n";
echo "BookmarkLevel: 1\n";
echo "BookmarkPageNumber: {$counter}\n";
$json[] = ["title" => "Índice","page" => $counter];

$counter = 1;
foreach($lines as $line) {
  if(preg_match("/(<h1>)/", $line)) {

    $line = str_replace(" <strong>" , "____", $line);
    $line = str_replace("</strong>" , "", $line);
    $line = strip_tags($line);
    $temp = explode("____", $line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: ".$temp[1]."\n";
    echo "BookmarkLevel: 1\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $temp[1],"page" => $counter, "tag" => "H1"];
  } else if(preg_match("/(<h2>)/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 2\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter, "tag" => "H2"];
  } else if(preg_match("/(<h3>)/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 3\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter, "tag" => "H3"];
  } else if(preg_match("/(<h4>)/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 4\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter, "tag" => "H4"];
  } else if(preg_match("/saltopagina/", $line)) {
    $counter++;
  }
}

echo "BookmarkBegin\n";
echo "BookmarkTitle: Contraportada\n";
echo "BookmarkLevel: 1\n";
echo "BookmarkPageNumber: {$counter}\n";
$json[] = ["title" => "Contraportada","page" => $counter];

$html = file_get_contents(__DIR__ . "/../index.html");
//INDICE
$indice = "";
foreach ($json as $item) {
  if(isset($item['tag']) && in_array($item['tag'], ['H1', 'H2'])) $indice .= '<a href="#anchor' . $item['page'] . '" class="like' . $item['tag'] . '"><span>' . $item['page'] . '</span>' . $item['title'] . '</a>';
}
$html = str_replace("|INDICE|", $indice, $html);



$counter = 2;
$html = preg_replace_callback("/\"saltopagina\"/", function($matches) {
  global $counter;

  $matches[0] = '"saltopagina" id="anchor'.$counter.'"';
  $counter++;
  return $matches[0];
}, $html);


file_put_contents(__DIR__ . "/../index.html", $html);

function cleanLine($line) {
  $line = str_replace(array("  ", "   ", "\t", "\n", "\r"), "", $line);
  return $line;
}

function removeHtmlComments($content = '') {
  return preg_replace('/<!--(.|\s)*?-->/', '', $content);
}