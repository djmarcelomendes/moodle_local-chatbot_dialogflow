<?php
require_once( '../../../config.php' );
global $CFG,$DB;

$config = get_config('local_chatbot_dialogflow');
// print_r($config);

try {
    if(isset($_POST['message']) && isset($_SESSION['sessionID']) && $config->chatbot_history_enabled){
      $record = new stdClass();
      $record->sessionid    = $_SESSION['sessionID'];
      $record->text         = $_POST['message'];
      $record->timecreated  = time()+$_POST['seq'];
      $lastinsertid = $DB->insert_record('chatbot_dialogflow', $record);

      $response = new stdClass();
      $response->result = "Ok";
      echo json_encode($response);
    }
  }catch (Exception $e) {
      $response = new stdClass();
      $response->result = $e->getMessage();
      echo json_encode($response);
  }
