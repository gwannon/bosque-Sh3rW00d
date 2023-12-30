#!/bin/bash

chromium --no-sandbox --headless ---gpu --no-pdf-header-footer --disable-back-forward-cache-for-cache-control-no-store-page --print-to-pdf=./temp.pdf ./index.html

exiftool -overwrite_original -Title="El bosque de Sh3rW00d. Versión 0.1" ./temp.pdf

php pdfIndexGenerator.php > pdf.info

pdftk 'temp.pdf' update_info_utf8 'pdf.info' output 'temp2.pdf'

rm temp.pdf
rm pdf.info

./pagination.sh temp2.pdf bosque-Sh3rW00d.pdf

rm temp2.pdf
