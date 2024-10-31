<?php


class PYU_Settings_Controller
{

  function __construct()
  {
  }

  public function sanitize_pyu_plugin_option($input)
  {
    update_option('pyu_update_htaccess_files', true);
    return $input;
  }

  public function register_settings()
  {

    register_setting(
      'pyu_plugin_settings',
      'pyu_plugin_options',
      array($this, 'sanitize_pyu_plugin_option')
    );

    add_settings_section(
      'pyu_settings_section_bots',
      __('Block crawling bots from indexing your protected uploads', 'protect_your_uploads'),
      function () {
        // _e('Bots blocking', 'protect_your_uploads');
      },
      'protect-your-uploads-settings'
    );

    add_settings_section(
      'pyu_settings_section_dir_listing',
      __('Disable browsing your directory containing your protected uploads', 'protect_your_uploads'),
      function () {
        // _e('Bots blocking', 'protect_your_uploads');
      },
      'protect-your-uploads-settings'
    );

    add_settings_field(
      'pyu_settings_field_googlebot',
      __('Block Google-Bot', 'protect_your_uploads'),
      array($this, 'settings_field_googlebot_cb'),
      'protect-your-uploads-settings',
      'pyu_settings_section_bots',
      array('label_for' => 'pyu_settings_field_googlebot')
    );

    add_settings_field(
      'pyu_settings_field_facebook_bot',
      __('Block Facebook-Bot', 'protect_your_uploads'),
      array($this, 'checkbox_field'),
      'protect-your-uploads-settings',
      'pyu_settings_section_bots',
      array('label_for' => 'pyu_settings_field_facebook_bot')
    );

    add_settings_field(
      'pyu_settings_field_twitter_bot',
      __('Block Twitter-Bot', 'protect_your_uploads'),
      array($this, 'checkbox_field'),
      'protect-your-uploads-settings',
      'pyu_settings_section_bots',
      array('label_for' => 'pyu_settings_field_twitter_bot')
    );

    add_settings_field(
      'disable_directory_browsing',
      __('Disable directory browsing', 'protect_your_uploads'),
      array($this, 'settings_field_googlebot_cb'),
      'protect-your-uploads-settings',
      'pyu_settings_section_dir_listing',
      array('label_for' => 'disable_directory_browsing')
    );
  }

  public function checkbox_field($args)
	{
    $name = "pyu_plugin_options[" . esc_attr($args['label_for']) . "]";
    $options = get_option('pyu_plugin_options');
    $checkbox = isset($options[$args['label_for']]) ? ($options[$args['label_for']] ? 'checked' : '') : '';
    echo '<input type="checkbox" id="' . $name . '" name="' . $name . '" value="1" ' . $checkbox . '>';
	}

  public function setttings_field_directory_listing_cb($args) {
    $this->checkbox_field($args);
  }

  public function settings_field_googlebot_cb($args)
  {
    $this->checkbox_field($args);
  }
  
}

?>