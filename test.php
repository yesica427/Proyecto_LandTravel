<?php

$ch = curl_init('localhost:8080/usr');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
echo '<h1 />'. var_dump($result) .'</h1>';