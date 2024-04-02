<?php
    require_once("auth.php");
    $user = verifyUser($pdo);

    $stmt = $pdo->prepare("DELETE * FROM bookmarks WHERE accounts_id = ? AND locations_id = ?;");
    if (!$stmt->execute([$user['id'], $_GET['locationid']])) {
        http_response_code(0); die;
    }
    else {
        http_response_code(200);
    }