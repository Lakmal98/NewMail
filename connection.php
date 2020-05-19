<?php 

class Connection {
    public function __construct() {
        $this->credentials = "credentials.json";
        $this->client = $this->create_client();
    }

    public function get_client() {
        return $this->client;
    }

    public function get_credentials() {
        return $this->credentials;
    }

    public function is_connected() {
        return $this->is_connected;
    }

    public function get_unauthenticated_data() {
        $authUrl = $this->client->createAuthUrl();
        return "<a href='$authUrl'>Click here to link your account.</a>";
    }

    public function credentials_in_browser() {
        if(isset($_GET['code'])) {
            return true;
        } else {
            return false;
        }
    }

    private function storeToken($param) {
        $token = json_encode($param);
        $email="lakmlaepp@gmail.com";//Replace Your mail. ###only for this now###
        $sql = "INSERT INTO usertoken (email, token) VALUES ('{$email}', '{$token}');";
        require_once("db.php");
        $result = $dbConn->query($sql);
        $dbConn->close();
        if(!$result) {
            return "{message: 'Authorize app to continue', err:'Failed to add token to the database.'}";
        }
        return NULL;
    }

    private function getStoredToken() {
        $email="lakmlaepp@gmail.com";//Replace Your mail. ###only for this now###
        $sql = "SELECT token FROM usertoken WHERE email = '{$email}';";
        require_once("db.php");
        $result = $dbConn->query($sql)->fetch_assoc();
        $dbConn->close();
        return $result['token'];
    }

    public function create_client() {
            $client = new Google_Client();
            $client->setApplicationName('Gmail API PHP Quickstart');
            $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
            $client->setAuthConfig('credentials.json');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            // Load previously authorized token from a file, if it exists.
            // The file token.json stores the user's access and refresh tokens, and is
            // created automatically when the authorization flow completes for the first
            // time.
            $accessToken = $this->getStoredToken();//Take stored token
            if ($accessToken != NULL) {
                $client->setAccessToken($accessToken);
            }

            // If there is no previous token or it's expired.
            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                }
                elseif($this->credentials_in_browser()) {
                    $authCode = $_GET['code'];

                    // Exchange authorization code for an access token.
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    // Check to see if there was an error.
                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                }
                else {
                    $this->is_connected = false;
                    return $client;
                }

                // Save the token to a file.
                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                $result = $this->storeToken($client->getAccessToken());
                if($result !== NULL) {
                    echo $result;
                }
        } else {
            echo "<p> Not expired </p>";
        }

        $this->is_connected = true;
        return $client;
    }

}