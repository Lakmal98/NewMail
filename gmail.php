<?php

class Gmail {
    public function __construct($client) {
        $this->client = $client;
    }

    public function readLabels() {
        $service = new Google_Service_Gmail($this->client);

        // Print the labels in the user's account.
        $user = 'me';
        $results = $service->users_labels->listUsersLabels($user);

        $the_html = "";

        if (count($results->getLabels()) == 0) {
            $the_html .= "<p>No labels found.</p>";
        } else {
            $the_html .= "<p>Labels: </p>";
            foreach ($results->getLabels() as $label) {
            $the_html .= "<p>" . $label->getName() . "</p>";
            }
        }

        return $the_html;
    }


    function listMessages() {
        $service = new Google_Service_Gmail($this->client);
        $userId = 'me';

        $pageToken = NULL;
        $messages = array();
        $opt_param = array();
        do {
        try {
            if ($pageToken) {
            $opt_param['pageToken'] = $pageToken;
            }
            $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
            if ($messagesResponse->getMessages()) {
            $messages = array_merge($messages, $messagesResponse->getMessages());
            $pageToken = $messagesResponse->getNextPageToken();
            }
        } catch (Exception $e) {
            print 'An error occurred: ' . $e->getMessage();
        }
        } while ($pageToken);
    
        foreach ($messages as $message) {
            $this->getMessage($service, $userId, $message->getId());
        break;
        }
    
        return $messages;
    }

    function getMessage($service, $userId, $messageId) {
        try {
          $message = $service->users_messages->get($userId, $messageId);
          echo "<pre>";
          print_r($message);
          return $message;
        } catch (Exception $e) {
          print 'An error occurred: ' . $e->getMessage();
        }
      }
}