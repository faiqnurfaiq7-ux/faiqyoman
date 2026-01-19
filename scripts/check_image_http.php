<?php
$url = 'http://127.0.0.1:8000/storage/produk/45Skhokfg4dTHl1mBCmFsTD7o1AbWbhvuCPTGq2M.jpg';
$h = @get_headers($url, 1);
if ($h === false) {
    echo "ERROR: no response\n";
    exit(1);
}
if (isset($h[0])) echo $h[0] . PHP_EOL;
else echo "NO-HEADERS\n";
