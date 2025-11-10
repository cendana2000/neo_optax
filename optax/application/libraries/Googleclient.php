<?php

class Googleclient
{

    public function getOATHToken()
    {
        $client = new Google_Client();
        try {
            $scope = 'https://www.googleapis.com/auth/analytics.readonly';
            $client->setAuthConfig(FCPATH.'persada-1268a-firebase-adminsdk-oyjkc-13724dfd13.json');
            $client->addScope(Google_Service_FirebaseCloudMessaging::CLOUD_PLATFORM);
    
            $savedTokenJson = $this->readSavedToken();
    
            if ($savedTokenJson) {
                // the token exists, set it to the client and check if it's still valid
                $client->setAccessToken($savedTokenJson);
                $accessToken = $savedTokenJson;
                if ($client->isAccessTokenExpired()) {
                    // the token is expired, generate a new token and set it to the client
                    $accessToken = $this->generateToken($client);
                    $client->setAccessToken($accessToken);
                }
            } else {
                // the token doesn't exist, generate a new token and set it to the client
                $accessToken = $this->generateToken($client);
                $client->setAccessToken($accessToken);
            }
    
            $oauthToken = $accessToken["access_token"];
    
            return $oauthToken;
    
            
        } catch (Google_Exception $e) {}
       return false;
    }

    private function readSavedToken() {
        $tk = @file_get_contents(FCPATH.'writeable/cache/token.cache');
        if ($tk) return json_decode($tk, true); else return false;
      }

    private function writeToken($tk) {
        $path = FCPATH.'writeable/cache';
        if (!file_exists($path))
        {
            mkdir($path, 0775, true);
        }
        file_put_contents($path.'/token.cache',$tk);
    }

    private function generateToken($client)
    {
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();

        $tokenJson = json_encode($accessToken);
        $this->writeToken($tokenJson);

        return $accessToken;
    }

    public function sendNotification($token, $title, $body, $accessToken = '') {
        if(empty($accessToken)){
            $accessToken = $this->getOATHToken();
        }

        $arrtoken = $token;
        if (is_string($token)) {
            $arrtoken = [$token];
        }

        $result = [];
        foreach($arrtoken as $key => $val){
            $payload = ["message" => ["token" => $val, "notification"=>["title" => $title, "body"=> $body]]];
        
            $postdata = json_encode($payload);
            
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-Type: application/json' . "\r\nAuthorization: Bearer $accessToken",
                    'content' => $postdata
                )
            );
        
            $context  = stream_context_create($opts);
        
            array_push($result,json_decode(file_get_contents('https://fcm.googleapis.com/v1/projects/persada-1268a/messages:send', false, $context)));
        }
        
        return $result;
    }
}
