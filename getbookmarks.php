<?php
    require_once("auth.php");
    $user = verifyUser($pdo);

    $stmt = $pdo->prepare("SELECT * FROM bookmarks INNER JOIN locations ON locations.id = bookmarks.locations_id WHERE accounts_id = ?;");
    if (!$stmt->execute([$user['id']])) {
        http_response_code(0); die;
    }
    
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));