#!/bin/bash

php ./tools/generateDocs.php > ./metas.txt
chromium --no-sandbox --headless --gpu --no-pdf-header-footer --print-to-pdf=./temp.pdf ./index.html
pdftk 'temp.pdf' update_info_utf8 'metas.txt' output 'bosque-Sh3rW00d.pdf'
rm metas.txt
rm temp.pdf