<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}

$checked_att = $row['protected'] ? ' checked' : '';
$status_txt = $row['protected'] ? __('Protected', 'protect_your_uploads') : __('Unprotected', 'protect_your_uploads');

echo '<input type="checkbox" id="pyu_cb' . $row['post_id'] . '"'. $checked_att . '>';
echo '<span id="pyu_status' . $row['post_id'] . '">&nbsp;' . $status_txt . '</span>';