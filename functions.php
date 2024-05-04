<?php
    include('logindetails.php');
    //functions used in both main scripts and testing
    //TA functions aren't here, but they really ought to be. Just couldn't test them automatically so never moved them over. Oops

    function createUser($pdo, $id) {
        $statement = $pdo->prepare('INSERT INTO `accounts` (id, google_id) VALUES (null, ?);');
        try {
          if ($statement->execute([$id]) == false) {  
            return 0;
          }
          return 200;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
          return 0;
        }
      }

    function createBookmark($pdo, $userId, $osmId, $osmType) {
        try{ 
            $stmt = $pdo->prepare("SELECT * FROM locations WHERE osm_id = ? AND osm_type = ?;");
            if (!$stmt->execute([$osmId, $osmType])) {
                http_response_code(0); return 0;
            }
        
            if ($stmt->rowCount() == 0) {
                $stmt = $pdo->prepare('INSERT INTO locations (id, osm_id, osm_type) VALUES (null, ?, ?);');
                if (!$stmt->execute([$osmId, $osmType])) { http_response_code(1); die; }
        
                $stmt = $pdo->prepare("SELECT * FROM locations WHERE osm_id = ? AND osm_type = ?;");
                if (!$stmt->execute([$osmId, $osmType])) {
                    http_response_code(2); return 2;
                }
            }
        
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        
            $stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE locations_id = ? AND accounts_id = ?;");
            if (!$stmt->execute([$id, $userId])) {
                http_response_code(3); return 3;
            }
        
            if ($stmt->rowCount() == 0) {
                $stmt = $pdo->prepare("INSERT INTO bookmarks (locations_id, accounts_id) VALUES (?, ?);");
                $success = $stmt->execute([$id, $userId]);
            
                if ($success) {
                    http_response_code(200); return 200;
                } else {
                    http_response_code(4); return 4;
                }
            }
        
        } catch (Exception $e) {
            http_response_code(5); return 5;
        }
    }

    function deleteBookmark($pdo, $userId, $osmId, $osmType) {
        $stmt = $pdo->prepare("SELECT * FROM locations WHERE osm_id = ? AND osm_type = ?;");
        if (!$stmt->execute([$osmId, $osmType])) {
            http_response_code(0); return 0;
        }
    
        if ($stmt->rowCount() == 0) {
            http_response_code(0); return 0;
        }
    
        $locationId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    
        $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE accounts_id = ? AND locations_id = ?;");
        if (!$stmt->execute([$userId, $locationId])) {
            http_response_code(1); return 1;
        }
    
        http_response_code(200); return 200;
    }

    function getBookmarks($pdo, $userId) {
        $stmt = $pdo->prepare("SELECT * FROM bookmarks INNER JOIN locations ON locations.id = bookmarks.locations_id WHERE accounts_id = ?;");
        if (!$stmt->execute([$userId])) {
            http_response_code(0); return 0;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function deleteUser($pdo, $userId) {
        $stmt = $pdo->prepare("DELETE * FROM accounts WHERE id = ?;");
        if (!$stmt->execute([$userId])) {
            http_response_code(0); return 0;
        }
        
        $stmt = $pdo->prepare('DELETE * FROM bookmarks WHERE accounts_id = ?;');
        if (!$stmt->execute([$userId])) {
            http_response_code(0); return 0;
        }
    
        http_response_code(200); return 200;
    }

