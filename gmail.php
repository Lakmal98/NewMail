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
  $i=0;  
        foreach ($messages as $message) {
            $this->getMessage($service, $userId, $message->getId());
        if($i==1){
        break;
        } else {
            $i++;
        }
        
        }
    
        // return $messages;
    }

    function getMessage($service, $userId, $messageId) {
        try {
          $message = $service->users_messages->get($userId, $messageId);

          $filterLabels = array("UNREAD", "INBOX");//Filter this labels
        //   $fiterIncludes = array("UGVLE", "IS21", "assignment")
          $snippet = $message->snippet;
          $labels = $message->labelIds;
        //   if(in_array($filterLabels[0], $labels) && in_array($filterLabels[1], $labels)) {
            if(in_array($filterLabels[1], $labels)) {
                if (preg_match('[UGVLE|IS21]', $snippet )) {
                    $messageId = $message->id;
                }
          }
        //   echo($message->snippet);
        //   return $message;
        } catch (Exception $e) {
          print 'An error occurred: ' . $e->getMessage();
        }
      }
}