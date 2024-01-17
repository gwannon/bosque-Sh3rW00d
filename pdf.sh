#!/bin/bash

chromium --no-sandbox --headless ---gpu --no-pdf-header-footer --disable-back-forward-cache-for-cache-control-no-store-page --print-to-pdf=./temp.pdf ./index.html

exiftool -overwrite_original -Title="Bosque Sh3rW00d. Versión 0.1" -Author="@Gwannon" -Subject="Bosque Sh3rW00d es un ambientación cyberpunk para Savage Worlds en un mundo muy parecido a las historias de Robin Hood o Ivanhoe. Versión 0.1" ./temp.pdf


php pdfIndexGenerator.php index.html > pdf.info

pdftk 'temp.pdf' update_info_utf8 'pdf.info' output 'temp2.pdf'

rm temp.pdf
rm pdf.info

./pagination.sh temp2.pdf bosque-Sh3rW00d.pdf

rm temp2.pdf
