<?php
    try {
        require_once("auth.php");

        $user = verifyUser($pdo);
        $stmt = $pdo->prepare("SELECT * FROM locations WHERE osm_id = ? AND osm_type = ?;");
        if (!$stmt->execute([$_GET['osm_id'], $_GET['osm_type']])) {
            http_response_code(0); die;
        }
    
        if ($stmt->rowCount() == 0) {
            $stmt = $pdo->prepare('INSERT INTO locations (id, osm_id, osm_type) VALUES (null, ?, ?);');
            if (!$stmt->execute([$_GET['osm_id'], $_GET['osm_type']])) { http_response_code(1); die; }

            $stmt = $pdo->prepare("SELECT * FROM locations WHERE osm_id = ? AND osm_type = ?;");
            if (!$stmt->execute([$_GET['osm_id'], $_GET['osm_type']])) {
                http_response_code(2); die;
            }
        }

        $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        $stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE locations_id = ? AND accounts_id = ?;");
        if (!$stmt->execute([$id, $user['id']])) {
            http_response_code(3); die;
        }

        if ($stmt->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO bookmarks (locations_id, accounts_id) VALUES (?, ?);");
            $success = $stmt->execute([$id, $user['id']]);
        
            if ($success) {
                http_response_code(200);
            } else {
                http_response_code(4);
            }
        }

    } catch (Exception $e) {
        http_response_code(5);
    }
