<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}
/*
  $users
*/
echo "<select name='pyu_select_user_" . $row['post_id'] . "' id='pyu_select_user_" . $row['post_id'] . "'>";
foreach ($users as $user) {
  $selected = ($row['user_id'] == $user->ID) ? ' selected' : '';
  echo "<option value='" . $user->ID . "'". $selected . ">" . $user->username . "</option>";
}
echo "</select>";