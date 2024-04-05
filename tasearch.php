<?php
    require_once("auth.php");
    $user = verifyUser($pdo);

    $url = 'https://api.content.tripadvisor.com/api/v1/location/search?key=' . $taKey . '&latLong=' . $_GET['lat'] . ',' . $_GET['lon'] . '&radius=5&radiusUnit=km&searchQuery=' . urlencode($_GET['query']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    echo $output;
