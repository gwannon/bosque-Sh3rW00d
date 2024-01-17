#!/bin/bash

chromium --no-sandbox --headless ---gpu --no-pdf-header-footer --disable-back-forward-cache-for-cache-control-no-store-page --print-to-pdf=./temp.pdf ./ci.html

exiftool -overwrite_original -Title="Ciberimplantes - Reglas rápidas para el juego de rol Savage Worlds." -Author="@Gwannon" -Subject="Reglas rápidas para ciberimplantes para el juego de rol Savage Worlds. Versión 1" ./temp.pdf


php pdfIndexGenerator.php ci.html > pdf.info

pdftk 'temp.pdf' update_info_utf8 'pdf.info' output 'temp2.pdf'

rm temp.pdf
rm pdf.info

./pagination.sh temp2.pdf ciberimplantes.pdf

rm temp2.pdf
