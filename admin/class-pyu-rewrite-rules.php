<?php

class PYU_RewriteRules
{

  private static $query_var_filename = 'pyu_filename';
  private static $query_var_fileext = 'pyu_fileext';

  public function add_query_variables($qvars)
  {
    $qvars[] = self::$query_var_filename;
    $qvars[] = self::$query_var_fileext;
    return $qvars;
  }

  private function shall_block_google_bot()
  {
    if (isset(get_option('pyu_plugin_options')['pyu_settings_field_googlebot'])) return true;
    else return false;
  }

  private function block_google($rules) {
    $new_rules = $rules . 'RewriteCond %{HTTP_USER_AGENT} Googlebot/[0-9] [OR]';
    return $new_rules;
  }

  private function shall_block_twitter_bot() {
    if (isset(get_option('pyu_plugin_options')['pyu_settings_field_twitter_bot'])) return true;
    else return false;
  }

  private function block_twitter($rules) {
    $new_rules = $rules . 'RewriteCond %{HTTP_USER_AGENT} Twitterbot/[0-9] [OR]';
    return $new_rules;
  }

  private function shall_block_facebook_bot() {
    if (isset(get_option('pyu_plugin_options')['pyu_settings_field_facebook_bot'])) return true;
    else return false;
  }

  private function block_facebook($rules) {
    $new_rules = $rules . 'RewriteCond %{HTTP_USER_AGENT} facebookexternalhit/[0-9] [OR]';
    return $new_rules;
  }

  private function block_bot_htaccess_content() {
    $rules = PHP_EOL . '# Protect Your Uploads Plugin - Start Bot Blocking' . PHP_EOL;
    $block_bots = ($this->shall_block_google_bot() || $this->shall_block_facebook_bot() || $this->shall_block_twitter_bot());
    if ($block_bots) {
      if ($this->shall_block_google_bot()) {
        $rules .= PHP_EOL;
        $rules = $this->block_google($rules);
      }
      if ($this->shall_block_facebook_bot()) {
        $rules .= PHP_EOL;
        $rules = $this->block_facebook($rules);
      }
      if ($this->shall_block_twitter_bot()) {
        $rules .= PHP_EOL;
        $rules = $this->block_twitter($rules);
      }
      $rules = substr($rules, 0, -5);
      $rules .= PHP_EOL . 'RewriteRule ' . PYU_Config::$upload_dir . '/' . "([A-Za-z0-9][A-Za-z0-9\s_@+\/&-]*[A-Za-z0-9_@+\/&-])\.([A-Za-z0-9]+)$ ";
      $rules .= "- [F,L]" . PHP_EOL;
    }
    $rules .= PHP_EOL . '# Protect Your Uploads Plugin - End Bot Blocking' . PHP_EOL;
    return $rules;
  }

  
  public function add_htaccess_content($rules)
  {
    $add_rule = '' . PHP_EOL;
    $add_rule .= '# Protect Your Uploads Plugin - Rules Start' . PHP_EOL;
    $add_rule .= $this->block_bot_htaccess_content();
    $add_rule .= 'RewriteCond %{REQUEST_FILENAME} -s' . PHP_EOL;
    $add_rule .= 'RewriteRule ' . PYU_Config::$upload_dir . '/' . "([A-Za-z0-9][A-Za-z0-9\s_@+\/&-]*[A-Za-z0-9_@+\/&-])\.([A-Za-z0-9]+)$ ";
    $add_rule .= "index.php?" . self::$query_var_filename . "=$1&" . self::$query_var_fileext . "=$2 [QSA,L]" . PHP_EOL;
    $add_rule .= '# Protect Your Uploads Plugin - Rules End' . PHP_EOL;
    $add_rule .= PHP_EOL;
    return $add_rule . $rules;
  }

  public function check_flush()
  {
    if (get_option('pyu_update_htaccess_files', false)) {
      flush_rewrite_rules();
      delete_option('pyu_update_htaccess_files');
    }
  }
}
