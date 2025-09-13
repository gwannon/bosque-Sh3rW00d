#!/bin/bash

php ./tools/generateDocs.php Bosque-Sh3rW00d
chromium --no-sandbox --headless --gpu --no-pdf-header-footer --print-to-pdf=./temp.pdf ./Bosque-Sh3rW00d.html
pdftk 'temp.pdf' update_info_utf8 'metas.txt' output 'Bosque-Sh3rW00d.pdf'
rm metas.txt
rm temp.pdf

php ./tools/generateDocs.php AventurasBosque-Sh3rW00d
chromium --no-sandbox --headless --gpu --no-pdf-header-footer --print-to-pdf=./temp.pdf ./AventurasBosque-Sh3rW00d.html
pdftk 'temp.pdf' update_info_utf8 'metas.txt' output 'AventurasBosque-Sh3rW00d.pdf'
rm metas.txt
rm temp.pdf