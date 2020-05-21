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


    public function listMessages() {
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
        if($i==15){
        break;
        } else {
            $i++;
        }
        
        }
    
        // return $messages;
    }

    private function sendSMS($message) {
        $user = "94775277373";
        $password = "1215";
        $text = urlencode($message);
        $to = "94775277373";
        
        $baseurl ="http://www.textit.biz/sendmsg";
        $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
        $ret = file($url);
        
        $res= explode(":",$ret[0]);
        
        if (trim($res[0])=="OK")
        {
        echo "Message Sent - ID : ".$res[1];
        }
        else
        {
        echo "Sent Failed - Error : ".$res[1];
        }

    }

    public function getMessage($service, $userId, $messageId) {
        try {
          $message = $service->users_messages->get($userId, $messageId);

          $filterLabels = array("UNREAD", "INBOX");//Filter this labels
        //   $fiterIncludes = array("UGVLE", "IS21", "assignment")
          $snippet = substr($message->snippet, 0, 154) . " ..."; //Take first 154 Characters to SMS
          $labels = $message->labelIds;
          if(in_array($filterLabels[0], $labels) && in_array($filterLabels[1], $labels)) {
            // if(in_array($filterLabels[1], $labels)) {
                if (preg_match('[UGVLE|IS21]', $snippet )) {
                    $data = array($message->id, $this->$snippet);
                    return $data;
                }
          }
        //   echo($message->snippet);
        //   return $message;
        } catch (Exception $e) {
          print 'An error occurred: ' . $e->getMessage();
        }
      }
}