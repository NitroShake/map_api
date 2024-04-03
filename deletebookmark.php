<?php
    require_once("auth.php");
    $user = verifyUser($pdo);
    echo 'e';

    $stmt = $pdo->prepare("SELECT * FROM locations WHERE osm_id = ? AND osm_type = ?;");
    if (!$stmt->execute([$_GET['osm_id'], $_GET['osm_type']])) {
        http_response_code(0); die;
    }

    if ($stmt->rowCount() == 0) {
        http_response_code(0); die;
    }

    $locationId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

    $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE accounts_id = ? AND locations_id = ?;");
    if (!$stmt->execute([$user['id'], $locationId])) {
        http_response_code(1); die;
    }

    http_response_code(200);
    