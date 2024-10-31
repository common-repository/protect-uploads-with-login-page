<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { 
	die;
}
?>
<h1><?php _e('Protect Your Uploads - Files Overview', 'protect_your_uploads'); ?></h1>

<div class="pyu-overview-content">

<table class="widefat pyu-file-overview" cellspacing="0">
  <thead>
    <tr>
      <!-- <th id="cb" class="manage-column column-cb check-column" scope="col"></th> -->
      <th><?php _e('File', 'protect_your_uploads'); ?></th>
      <th style="white-space: nowrap;"><?php _e('Status', 'protect_your_uploads'); ?></th>
      <th><?php _e('User', 'protect_your_uploads'); ?></th>
      <th><?php _e('Action', 'protect_your_uploads'); ?></th>
      <th><?php _e('Directlink', 'protect_your_uploads'); ?></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
    <th><?php _e('File', 'protect_your_uploads'); ?></th>
      <th style="white-space: nowrap;"><?php _e('Status', 'protect_your_uploads'); ?></th>
      <th><?php _e('User', 'protect_your_uploads'); ?></th>
      <th><?php _e('Action', 'protect_your_uploads'); ?></th>
      <th><?php _e('Directlink', 'protect_your_uploads'); ?></th>
    </tr>
  </tfoot>

  <tbody>

    <?php

      for ($i = 0; $i < count($file_rows); $i++) {

        $row = $file_rows[$i];

        $tr_class = ($i % 2) ? 'alternate' : '';
        echo "<tr class=\"{$tr_class}\">";

        echo '<td>';
        echo $row['filename'];
        echo '</td>';

        echo '<th style="white-space: nowrap;">';
        include('file_dashboard_status.php');
        echo '</td>';

        echo '<td>';
        include('file_dashboard_user_selection.php');
        echo '</td>';

        echo '<td>';
        include('file_dashboard_save_button.php');
        echo '</td>';

        echo '<td>';
        echo '<a target="_blank" id="guid' . $row['post_id'] . '" href="' . $row['guid'] .'">' . $row['guid'] . '</a>';
        echo '</td>';

        echo '</tr>';

      }

    ?>

</tbody>
</table>
</div>