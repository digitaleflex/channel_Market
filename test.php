<?php

$ch = curl_init('https://api.charriow.com/v1/checkout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$res = curl_exec($ch);
if ($res === false) {
    echo 'ERROR: '.curl_error($ch).PHP_EOL;
} else {
    echo $res;
}
curl_close($ch);
