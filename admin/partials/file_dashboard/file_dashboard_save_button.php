<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}
$button_text = __('Save', 'protect_your_uploads');
echo "<button class='button-primary' id='" . "pyu_save_button_" . $row['post_id'] . "' onclick='";
echo "pyu_send_admin_request(" . $row['post_id'] . ");'>";
echo $button_text;
echo "</button>";