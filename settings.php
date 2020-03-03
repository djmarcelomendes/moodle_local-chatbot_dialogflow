<?php
// This file is part of the Local Chatbot Dialogflow plugin
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This plugin verify group user ldap and create
 * cohort same name specified settings
 *
 * @package    local
 * @subpackage chatboot_dialogflow
 * @copyright  2019 Marcelo Mendes, m2msolucoes.com.br
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

  $site = get_site();
  global $CFG;

    $settings = new admin_settingpage('local_chatbot_dialogflow', get_string('pluginname', 'local_chatbot_dialogflow'));
    $ADMIN->add('localplugins', $settings);

    $name = 'local_chatbot_dialogflow/chatbot_enabled';
    $title = get_string('chatbot_enabled', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_enabled_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_titulo';
    $default = 'Robô '.$site->fullname;
    $title = get_string('chatbot_titulo', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_titulo_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_NOTAGS);
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_topcolor';
    $title = get_string('chatbot_topcolor', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_topcolor_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#162e62');
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_topcolor_text';
    $title = get_string('chatbot_topcolor_text', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_topcolor_text_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#FFFFFF');
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_clientkey';
    $title = get_string('chatbot_clientkey', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_clientkey_desc', 'local_chatbot_dialogflow');
    $opts = array('accepted_types' => array('.json'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'attachment', 0, $opts);
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_dist_right';
    $title = get_string('chatbot_dist_right', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_dist_right_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configtext($name, $title, $description, '32', PARAM_INT);
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_dist_bottom';
    $title = get_string('chatbot_dist_bottom', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_dist_bottom_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configtext($name, $title, $description, '20', PARAM_INT);
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_button_color';
    $title = get_string('chatbot_button_color', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_button_color_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#162e62');
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_button_color_text';
    $title = get_string('chatbot_button_color_text', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_button_color_text_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#FFFFFF');
    $settings->add($setting);

    $var_script = '<script type="text/javascript" id="chatboot_dialogflow-embedder-d7lcfheammjct" class="chatboot_dialogflow-embedder-d7lcfheammjct" data-botId="5d5c5f2a848d3754b5eefc9c">
      var s = document.createElement("script");
      s.type = "text/javascript";
      s.async = true;
      s.src = "'.$CFG->wwwroot.'/local/chatbot_dialogflow/js/injection.js";
      document.getElementById("chatboot_dialogflow-embedder-d7lcfheammjct").appendChild(s);
      </script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.0/showdown.js"></script>';

    $name = 'local_chatbot_dialogflow/chatbot_text_script';
    $title = get_string('chatbot_text_script', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_text_script_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configtextarea($name, $title, $description, $var_script);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_head_history';
    $title = get_string('chatbot_head_history', 'local_chatbot_dialogflow');
    $settings->add(new admin_setting_heading($name, $title, ''));

    $name = 'local_chatbot_dialogflow/chatbot_history_enabled';
    $title = get_string('chatbot_history_enabled', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_history_enabled_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $settings->add($setting);

    $name = 'local_chatbot_dialogflow/chatbot_history_expire';
    $title = get_string('chatbot_history_expire', 'local_chatbot_dialogflow');
    $description = get_string('chatbot_history_expire_desc', 'local_chatbot_dialogflow');
    $setting = new admin_setting_configtext($name, $title, $description, '0', PARAM_INT);
    $settings->add($setting);

}
