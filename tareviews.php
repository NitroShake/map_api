<?php
    require_once("auth.php");
    $user = verifyUser($pdo);

    $url = 'https://api.content.tripadvisor.com/api/v1/location/' . $_GET['id'] . '/reviews?key=' . $taKey;
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    echo $output;