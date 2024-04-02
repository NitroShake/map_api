<?php 
    require_once("auth.php");
    $user = verifyUser($pdo);

    $stmt = $pdo->prepare("UPDATE locations SET lat = ?, lon = ?, name = ? WHERE id = ");
    if (!$stmt->execute([$_GET['lat'], $_GET['lon'], $_GET['name'], $_GET['locationid']])) {
        http_response_code(0); die;
    }
    http_response_code(200);