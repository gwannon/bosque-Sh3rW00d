<?php
/*preg_match("/<body[^>]*>(.*?)<\/body>/is", file_get_contents('index.html'), $matches);
print_r ($matches);
die;*/
$doc = new DOMDocument();
$internalErrors = libxml_use_internal_errors(true);
$doc->loadHTMLFile($argv[1]);
$body = $doc->getElementsByTagName('body');
$body = $body->item(0);
$json = [];

$html = explode("\n", removeHtmlComments($doc->savehtml($body)));

$lines = [];
foreach($html as $line) {
  $line = cleanLine($line);
  if(preg_match("/(<h1>|<h2>|<h2 id|<h3|<h3 id|<h4|<h4 id|saltopagina)/", $line)) $lines[] = $line;
}
$counter = 1;
echo "BookmarkBegin\n";
echo "BookmarkTitle: Portada\n";
echo "BookmarkLevel: 1\n";
echo "BookmarkPageNumber: {$counter}\n";
$json[] = ["title" => "Portada","page" => $counter];
foreach($lines as $line) {
  if(preg_match("/(<h1>)/", $line)) {
    preg_match("/<span>(.*)<\/span>/", $line, $matches);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$matches[1]}\n";
    echo "BookmarkLevel: 1\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter];
  } else if(preg_match("/<h2/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 2\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter];
  } else if(preg_match("/<h3/", $line)) {
    $line = strip_tags($line);
    echo "BookmarkBegin\n";
    echo "BookmarkTitle: {$line}\n";
    echo "BookmarkLevel: 3\n";
    echo "BookmarkPageNumber: {$counter}\n";
    $json[] = ["title" => $line,"page" => $counter];
  } else if(preg_match("/<h4/", $line)) {
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

file_put_contents ("./indice.json", json_encode($json));



function cleanLine($line) {
  $line = str_replace(array("  ", "   ", "\t", "\n", "\r"), "", $line);
  return $line;
}

function removeHtmlComments($content = '') {
  return preg_replace('/<!--(.|\s)*?-->/', '', $content);
}