<?php

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
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 1\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter];
  } else if(preg_match("/(<h2>)/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 2\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter];
  } else if(preg_match("/(<h3>)/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 3\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter];
  } else if(preg_match("/(<h4>)/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 4\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter];
  } else if(preg_match("/saltopagina/", $line)) {
    $counter++;
  }
}

echo "BookmarkBegin\n";
echo "BookmarkTitle: Contraportada\n";
echo "BookmarkLevel: 1\n";
echo "BookmarkPageNumber: {$counter}\n";
$json[] = ["title" => "Contraportada","page" => $counter];

file_put_contents (__DIR__ . "/../assests/js/indice.js", "var indice = ".json_encode($json)).";";



function cleanLine($line) {
  $line = str_replace(array("  ", "   ", "\t", "\n", "\r"), "", $line);
  return $line;
}

function removeHtmlComments($content = '') {
  return preg_replace('/<!--(.|\s)*?-->/', '', $content);
}