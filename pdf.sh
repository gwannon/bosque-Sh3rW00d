#!/bin/bash

php ./tools/generateDocs.php > ./metas.txt
chromium --no-sandbox --headless --gpu --no-pdf-header-footer --print-to-pdf=./bosque-Sh3rW00d.pdf ./index.html
pdftk 'bosque-Sh3rW00d.pdf' update_info_utf8 'metas.txt' output 'temp.pdf'
rm metas.txt
./tools/pagination.sh temp.pdf bosque-Sh3rW00d.pdf
rm temp.pdf