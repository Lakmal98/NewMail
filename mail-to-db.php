<?php

class NewMail {
    public function __construct() {
        $this->include();
    }

    private function include() {
        require __DIR__ . '/vendor/autoload.php';
        include 'connection.php';
    }

    public function go() {
        $conn = new Connection();

        if($conn->is_connected()){
            require_once('gmail.php');
            $gmail = new Gmail($conn->get_client());
            return print_r($gmail->listMessages()); 
        } else {
            return $conn->get_unauthenticated_data();
        }
    }
}

$newMail = new NewMail();

echo "<!Doctype html><html>";
print_r($newMail->go());
echo "</html>";
