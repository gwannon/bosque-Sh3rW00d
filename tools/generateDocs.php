<?php

require __DIR__ . '/../vendor/autoload.php';
include(__DIR__ ."/config".$argv[1].".php");

/* Generamos el HTML */
/* -------------------------------------------------------------- */
use FastVolt\Helper\Markdown;

$md = file_get_contents(__DIR__ . "/../".$argv[1].".md");

$md = preg_replace_callback("/\|([0-9a-zA-Z]*)\.md\|/", function($matches) {
  $matches[0] = file_get_contents(__DIR__ . "/../".$matches[1].".md"); 
  return $matches[0];
}, $md);

file_put_contents(__DIR__ . "/../Acc".$argv[1].".md", str_replace(["\n\n\n\n", "\n\n\n\n", "\n\n\n", "\n\n\n\n", "\n\n\n\n", "\n\n\n"], "\n\n", str_replace(["\n\n\sp", "\n\n\sc", "\n\n\sinc", "\n\n\conc", "\n\n&nbsp;"], "", $md)));


$mkd = Markdown::new();
$mkd->setContent($md);
$tags['HTML'] = $mkd->toHtml();
$html = file_get_contents(__DIR__ . "/template.html");
foreach ($tags as $tag => $value) {
  $html = str_replace("|".$tag."|", $value, $html); 
}
$html = str_replace("<hr />", "</div><div class=\"saltopagina\"></div>\n</section>\n<section>", $html); 
$html = str_replace("<p>\sp</p>", "</div><div class=\"saltopagina\"></div><div class=\"columns\">", $html);
$html = str_replace("<p>\sc</p>", "<p class=\"saltocolumna\"></p>", $html);
$html = str_replace("<p>\sinc</p>", "</div>", $html);
$html = str_replace("[br]", "<br/>", $html);
$html = str_replace("<p>\conc</p>", "<div class=\"columns\">", $html);
$html = str_replace("</h1>", "</h1>\n<div class=\"saltopagina\"></div><div class=\"columns\">", $html); 
$html = str_replace("<div class=\"columns\">\n</div>", "", $html); 
$html = str_replace("<table>", "<div><table>", $html); 
$html = str_replace("</table>", "</table></div>", $html); 
$html = str_replace("<p><strong>Semilla de aventura:</strong>", "<p class=\"seed\"><strong>Semilla de aventura:</strong>", $html);

file_put_contents(__DIR__ . "/../".$argv[1].".html", $html);

/* Generamos Metas */
/* -------------------------------------------------------------- */
$metas = "InfoKey: Subject\n";
$metas .= "InfoValue: ".$tags['DESCRIPTION']." Versión ".$tags['VERSION']."\n\n";
$metas .= "InfoKey: Author\n";
$metas .= "InfoValue: ".$tags['AUTHOR']."\n\n";
$metas .= "InfoKey: Keywords\n";
$metas .= "InfoValue: ".$tags['KEYWORDS']."\n\n";

/* Generamos indice del PDF */
/* -------------------------------------------------------------- */
$doc = new DOMDocument();
$internalErrors = libxml_use_internal_errors(true);
$doc->loadHTMLFile(__DIR__ . "/../".$argv[1].".html");
$body = $doc->getElementsByTagName('body');
$body = $body->item(0);
$json = [];
$lines = [];

foreach(explode("\n", removeHtmlComments($doc->savehtml($body))) as $line) {
  $line = cleanLine($line);
  if(preg_match("/(<h1>|<h2|<h3|<h4|saltopagina)/", $line)) $lines[] = $line;
}

$metas .= bookMark("Portada", 1, 1);
$json[] = ["title" => "Portada","page" => 1];
$metas .= bookMark("Licencia de uso", 1, 3);
$json[] = ["title" => "Licencia de uso","page" => 3];
$metas .= bookMark("Índice", 1, 4);
$json[] = ["title" => "Índice","page" => 4];

$counter = 1;
foreach($lines as $line) {
  if(preg_match("/(<h1>)/", $line)) {
    $line = str_replace("</strong>" , "", str_replace(" <strong>" , "____", $line));
    $line = strip_tags($line);
    $temp = explode("____", $line);
    $metas .= bookMark($temp[1], 1, $counter);
    $json[] = ["title" => $temp[1],"page" => $counter, "tag" => "H1"];
  } else if(preg_match("/(<h2>)/", $line)) {
    $line = strip_tags($line);
    $metas .= bookMark($line, 2, $counter);
    $json[] = ["title" => $line,"page" => $counter, "tag" => "H2"];
  } else if(preg_match("/(<h3>)/", $line)) {
    $line = strip_tags($line);
    $metas .= bookMark($line, 3, $counter);
    $json[] = ["title" => $line,"page" => $counter, "tag" => "H3"];
  } else if(preg_match("/(<h4>)/", $line)) {
    $line = strip_tags($line);
    $metas .= bookMark($line, 4, $counter);
    $json[] = ["title" => $line,"page" => $counter, "tag" => "H4"];
  } else if(preg_match("/saltopagina/", $line)) {
    $counter++;
  }
}

$metas .= bookMark("Contraportada", 1, $counter);
$json[] = ["title" => "Contraportada", "page" => $counter];

echo "Indice ".count($json)." items\n";

/* Generamos el Índice HTML */
/* -------------------------------------------------------------- */
$indice = "";
foreach ($json as $item) {
  if(isset($item['tag']) && in_array($item['tag'], ['H1', 'H2'])) $indice .= '<a href="#anchor' . $item['page'] . '" class="like' . $item['tag'] . '"><span>' . $item['page'] . '</span>' . $item['title'] . '</a>';
}
$html = str_replace("|INDICE|", $indice, $html);

$html = str_replace("|BODY|", $argv[1], $html);

$counter = 2;
$html = preg_replace_callback("/\"saltopagina\"/", function($matches) {
  global $counter;
  if ($counter%2 == 0) $matches[0] = '"saltopagina even" id="anchor'.$counter.'"';
  else $matches[0] = '"saltopagina" id="anchor'.$counter.'"';
  $counter++;
  return $matches[0];
}, $html);

file_put_contents(__DIR__ . "/../".$argv[1].".html", $html);
file_put_contents(__DIR__ . "/../metas.txt", $metas);

/* LIBs */
/* -------------------------------------------------------------- */

function cleanLine($line) {
  return str_replace(array("  ", "   ", "\t", "\n", "\r"), "", $line);
}

function bookMark($title, $level, $counter) {
  $title = htmlspecialchars_decode($title);
  $metas = "BookmarkBegin\n";
  $metas .= "BookmarkTitle: {$title}\n";
  $metas .= "BookmarkLevel: {$level}\n";
  $metas .= "BookmarkPageNumber: {$counter}\n";
  return $metas;
}

function removeHtmlComments($content = '') {
  return preg_replace('/<!--(.|\s)*?-->/', '', $content);
}