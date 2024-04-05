<?php
    require_once 'google-api-php-client--PHP8.0/vendor/autoload.php';
    require_once 'logindetails.php';
    $pdo = new PDO("mysql:host=localhost;  dbname=map_app;", $dbLoginUsername, $dbLoginPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function createUser($pdo, $payload) {
      $statement = $pdo->prepare('INSERT INTO `accounts` (id, google_id) VALUES (null, ?);');
      try {
        if ($statement->execute([$payload['sub']]) == false) {  
          die;
        }
      }
      catch (PDOException $e) {
        echo $e->getMessage();
      }
    }

    function verifyUser($pdo) {
        $CLIENT_ID = '465811042306-dom6qsketvf42g5k2v7uva69memphqg5.apps.googleusercontent.com';
        try {
          $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
          $client->addScope("email");
          //$client->setAccessToken($_POST['access_token']);
          $payload = $client->verifyIdToken($_POST['id_token']);
          if ($payload) {
            $statement = $pdo->prepare('SELECT * FROM accounts WHERE google_id = ?;');
            $statement->execute([$payload['sub']]);
            if ($statement->rowCount() < 1) {
              createUser($pdo, $payload);
              $statement = $pdo->prepare('SELECT * FROM accounts WHERE google_id = ?;');
              $statement->execute([$payload['sub']]);
            }
            $payload['id'] = $statement->fetch(PDO::FETCH_ASSOC)['id'];
            return $payload;
            //$userid = $payload['sub'];
      
          } else {
            echo 'Invalid User';
            die;
          }
    
        } catch (Exception $e) {
          echo 'API Error' . $e->getMessage();
        }
    }
?>