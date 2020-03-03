<?php
require_once( '../../../config.php' );
global $CFG, $DB;

$config = get_config('local_chatbot_dialogflow');
// print_r($config);

$html = '';

if( $config->chatbot_enabled )
{
  $extra_history = '';
  $history = $DB->get_records('chatbot_dialogflow', array('sessionid' => $_SESSION['sessionID']));
  foreach ($history as $his) {
    $extra_history .= '<div class="row">'.$his->text.'</div>';
  }
  $html = '
  <link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/chatbot_dialogflow/css/chatbot.css" media="all">
  <link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/chatbot_dialogflow/css/lightslider.css" media="all">
  <input type="hidden" name="chatbot_topcolor" value="'.$config->chatbot_topcolor.'" />
  <div id="Smallchat">
    <div class="Layout Layout-open Layout-expand Layout-right" style="background-color: #3F51B5;opacity: 5;border-radius: 10px;">
      <div class="Messenger_messenger">
        <div class="Messenger_header" style="background-color: '.$config->chatbot_topcolor.'; color: '.$config->chatbot_topcolor_text.';">
          <div class="Messenger_prompt">'.$config->chatbot_titulo.'</div><div class="mck-agent-status-text" style="color: '.$config->chatbot_topcolor_text.';">Online</div><span class="chat_close_icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve"><g><g><path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M26,50C12.767,50,2,39.233,2,26   S12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z" data-original="#000000" class="active-path" data-old_color="#000000" fill="'.$config->chatbot_topcolor_text.'"/><path d="M35.707,16.293c-0.391-0.391-1.023-0.391-1.414,0L26,24.586l-8.293-8.293c-0.391-0.391-1.023-0.391-1.414,0   s-0.391,1.023,0,1.414L24.586,26l-8.293,8.293c-0.391,0.391-0.391,1.023,0,1.414C16.488,35.902,16.744,36,17,36   s0.512-0.098,0.707-0.293L26,27.414l8.293,8.293C34.488,35.902,34.744,36,35,36s0.512-0.098,0.707-0.293   c0.391-0.391,0.391-1.023,0-1.414L27.414,26l8.293-8.293C36.098,17.316,36.098,16.684,35.707,16.293z" data-original="#000000" class="active-path" data-old_color="#000000" fill="'.$config->chatbot_topcolor_text.'"/></g></g></svg></span>
        </div>
        <div class="Messenger_content">
          <div class="Messages" id="scroll">
            <div>
              <div class="Messages_list" id="chat-text">'.$extra_history.'</div>
            </div>
          </div>
          <div class="Input Input-blank">
            <form id=chatBotDialogflow>
              <input class="Input_field" placeholder="Escreva a sua mensagem..." id="message" name="date" value="" x-webkit-speech autocomplete="off">
              <button type="submit" class="Input_button Input_button-send">
                <div class="Icon" style="width: 25px; height: 25px;">
                  <svg viewBox="0 -66 511.99969 511" xmlns="http://www.w3.org/2000/svg"><g fill="#020202"><path d="m50 190.507812c0-5.523437-4.476562-10-10-10h-30c-5.523438 0-10 4.476563-10 10 0 5.523438 4.476562 10 10 10h30c5.523438 0 10-4.476562 10-10zm0 0"/><path d="m110.003906 250.507812c0-5.519531-4.476562-10-10-10h-60.003906c-5.523438 0-10 4.480469-10 10 0 5.523438 4.476562 10.003907 10 10.003907h60.003906c5.523438 0 10-4.480469 10-10.003907zm0 0"/><path d="m100.003906 140.503906c5.523438 0 10-4.476562 10-10 0-5.519531-4.476562-10-10-10h-60.003906c-5.523438 0-10 4.480469-10 10 0 5.523438 4.476562 10 10 10zm0 0"/><path d="m326.011719 190.507812c0 5.523438-4.476563 10-10 10-5.523438 0-10-4.476562-10-10 0-5.523437 4.476562-10 10-10 5.523437 0 10 4.476563 10 10zm0 0"/><path d="m506.371094 181.503906-372.011719-180.003906c-3.617187-1.753906-7.9375-1.167969-10.957031 1.488281-3.019532 2.65625-4.15625 6.859375-2.886719 10.675781l41.222656 123.667969-67.28125 44.855469c-2.785156 1.855469-4.453125 4.976562-4.453125 8.320312s1.667969 6.464844 4.453125 8.320313l67.28125 44.851563-41.222656 123.671874c-1.269531 3.8125-.132813 8.019532 2.886719 10.675782 3.03125 2.664062 7.347656 3.234375 10.957031 1.488281l372.011719-180.003906c7.414062-3.59375 7.59375-14.332031 0-18.007813zm-388.339844 9.003906 50.265625-33.507812 11.167969 33.507812-11.167969 33.507813zm79.183594 10h73.792968c5.523438 0 10.003907-4.476562 10.003907-10 0-5.523437-4.480469-10-10.003907-10h-73.792968l-50.226563-150.679687 311.402344 150.679687h-97.378906c-5.523438 0-10 4.476563-10 10 0 5.523438 4.476562 10 10 10h97.378906l-311.402344 150.679688zm0 0"/></g></svg>
                </div>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--===============CHAT ON BUTTON STRART===============-->
    <div class="chat_on" style="right: '.$config->chatbot_dist_bottom.'px; bottom: '.$config->chatbot_dist_right.'px; background-color: '.$config->chatbot_button_color.';">
      <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 356.484 356.484" style="enable-background:new 0 0 356.484 356.484;" xml:space="preserve">
        <path fill="'.$config->chatbot_button_color_text.'" d="M293.984,7.23H62.5C28.037,7.23,0,35.268,0,69.731v142.78c0,34.463,28.037,62.5,62.5,62.5l147.443,0.001l70.581,70.58 c2.392,2.393,5.588,3.662,8.842,3.662c1.61,0,3.234-0.312,4.78-0.953c4.671-1.934,7.717-6.49,7.717-11.547v-62.237 c30.759-3.885,54.621-30.211,54.621-62.006V69.731C356.484,35.268,328.447,7.23,293.984,7.23z M331.484,212.512 c0,20.678-16.822,37.5-37.5,37.5h-4.621c-6.903,0-12.5,5.598-12.5,12.5v44.064l-52.903-52.903 c-2.344-2.345-5.522-3.661-8.839-3.661H62.5c-20.678,0-37.5-16.822-37.5-37.5V69.732c0-20.678,16.822-37.5,37.5-37.5h231.484 c20.678,0,37.5,16.822,37.5,37.5V212.512z"/>
        <path fill="'.$config->chatbot_button_color_text.'" d="M270.242,95.743h-184c-6.903,0-12.5,5.596-12.5,12.5c0,6.903,5.597,12.5,12.5,12.5h184c6.903,0,12.5-5.597,12.5-12.5 C282.742,101.339,277.146,95.743,270.242,95.743z"/>
        <path fill="'.$config->chatbot_button_color_text.'" d="M270.242,165.743h-184c-6.903,0-12.5,5.596-12.5,12.5s5.597,12.5,12.5,12.5h184c6.903,0,12.5-5.597,12.5-12.5 S277.146,165.743,270.242,165.743z"/>
      </svg>
    </div>
    <!--===============CHAT ON BUTTON END===============-->
  </div>
  ';
}

echo json_encode(array('html' => $html));
