<?php
    require_once("auth.php");
    $user = verifyUser($pdo);

    $stmt = $pdo->prepare("DELETE * FROM accounts WHERE id = ?;");
    if (!$stmt->execute([$user['id']])) {
        http_response_code(0); die;
    }
    
    $stmt = $pdo->prepare('DELETE * FROM bookmarks WHERE accounts_id = ?;');
    if (!$stmt->execute([$user['id']])) {
        http_response_code(0); die;
    }

    http_response_code(200);