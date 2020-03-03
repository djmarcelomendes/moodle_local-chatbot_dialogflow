<?php
require __DIR__.'/vendor/autoload.php';
require_once( '../../config.php' );

if( empty($_SESSION['sessionID']) ){
	$_SESSION['sessionID'] = bin2hex(random_bytes(16));
}

try {
    if(isset($_POST['submit'])){
        // create curl resource
        $ch = curl_init();
        $userquery = $_POST['message'];
        $query = curl_escape($ch,$_POST['message']);
        $sessionid = curl_escape($ch,$_SESSION['sessionID']);

        $client = new \Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->setScopes (['https://www.googleapis.com/auth/dialogflow']);

        $context = context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'local_chatbot_dialogflow', 'attachment', 0, 'itemid, filepath, filename', false);

        $choices = [];
        foreach ($files as $file) {
           // $choices[$file->get_filename()] = $file->get_filename();
           // print_r($file);
           $contents = $file->get_content();

        }

        $auth_settings= json_decode($contents);
        $auth = [
            "type" => $auth_settings->type,
            "project_id" => $auth_settings->project_id,
            "private_key" => $auth_settings->private_key,
            "client_email" => $auth_settings->client_email,
            "client_id" => $auth_settings->client_id,
            "auth_uri" => $auth_settings->auth_uri,
            "token_uri" => $auth_settings->token_uri,
            "auth_provider_x509_cert_url" => $auth_settings->auth_provider_x509_cert_url,
            "client_x509_cert_url" => $auth_settings->client_x509_cert_url
        ];
        $client->setSubject($auth_settings->client_email);
        $client->setAuthConfig($auth);

        $httpClient = $client->authorize();

        $apiUrl = 'https://dialogflow.googleapis.com/v2/projects/'.$auth_settings->project_id.'/agent/sessions/'.$sessionid.':detectIntent';
        $response = $httpClient->request('POST',$apiUrl,[
            'json' => ['queryInput' => ['text'=>['text'=>$userquery,'languageCode'=>'en']],
                'queryParams'=>['timeZone'=>'Asia/Calcutta']]
        ]);


        $contents = $response->getBody()->getContents();
        $dec = json_decode($contents);

        $defaultResponse = '';
        $hasDefaultResponse = false;
        if( isset( $dec->queryResult->fulfillmentText ) ){
            $hasDefaultResponse = true;
            $defaultResponse = $dec->queryResult->fulfillmentText;
        }

        $isEndOfConversation=0;
        if( isset( $dec->queryResult->diagnosticInfo->end_conversation ) ){
            $isEndOfConversation = 1;
        }

        $messages = $dec->queryResult->fulfillmentMessages;
        $intentid = $dec->queryResult->intent->name;
        $intentname = $dec->queryResult->intent->displayName;
        $speech = '';
        // for($idx = 0; $idx < count($messages); $idx++){
            // $obj = $messages[$idx];
            // if($obj->platform=='ACTIONS_ON_GOOGLE'){
                // $simpleResponses = $obj->simpleResponses;
                // $speech = $simpleResponses->simpleResponses[0]->textToSpeech;
            // }
        // }


        $Parsedown = new Parsedown();
        $transformed= $Parsedown->text($speech);
        if($hasDefaultResponse){
            $response -> defaultResponse = $Parsedown->text($defaultResponse);
        }
        $response -> speech = $transformed;
        $response -> messages = $messages;
        $response -> isEndOfConversation = $isEndOfConversation;

        echo json_encode($response);
        // close curl resource to free up system resources
        curl_close($ch);
    }
}catch (Exception $e) {
    $speech = $e->getMessage();
    $fulfillment = new stdClass();
    $fulfillment->speech = $speech;
    $result = new stdClass();
    $result->fulfillment = $fulfillment;
    $response = new stdClass();
    $response->result = $result;
    echo json_encode($response);
}

?>
