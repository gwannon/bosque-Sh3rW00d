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
$html = str_replace("<p>\sincolumna</p>", "</div>", $html);
$html = str_replace("<p>\concolumna</p>", "<div class='columns'>", $html);
$html = str_replace("</h1>", "</h1>\n<div class=\"saltopagina\"></div><div class='columns'>", $html); 
$html = str_replace("<div class='columns'>\n</div>", "", $html); 
$html = str_replace("<table>", "<div><table>", $html); 
$html = str_replace("</table>", "</table></div>", $html); 


file_put_contents(__DIR__ . "/../index.html", $html);

echo "InfoKey: Subject\n";
echo "InfoValue: ".$tags['DESCRIPTION']." Versión ".$tags['VERSION']."\n\n";
echo "InfoKey: Author\n";
echo "InfoValue: ".$tags['AUTHOR']."\n\n";
echo "InfoKey: Keywords\n";
echo "InfoValue: ".$tags['KEYWORDS']."\n\n";

include(__DIR__ ."/pdfIndexGenerator.php");