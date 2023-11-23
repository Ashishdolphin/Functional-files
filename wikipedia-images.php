<?php
  require 'wp-config.php';

  $upload_dir = wp_upload_dir();
  $base_upload = $upload_dir['basedir'] . '/image-wikipedia';
  
  // Create an array to store file names for later comparison
  $filenames = array();

  foreach (glob($base_upload . '/*.*') as $file) {
    $filename = pathinfo($file, PATHINFO_FILENAME); // Get the filename without extension
    $filenames[] = $filename; // Store file names in an array
  }

  
if (!empty($filenames)) {
    global $wpdb;

  // Query attachments and compare with file names
    $attachments = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT ID, post_title, guid FROM $wpdb->posts WHERE post_type = 'attachment'"
      )
    );

    if (!empty($attachments)) {
        foreach ($attachments as $attachment) {
            $attachment_id = $attachment->ID;
            $attachment_post_title = $attachment->post_title;
          
            if (in_array($attachment_post_title, $filenames)) {
              
              wp_delete_attachment($attachment_id, true);
              
            }
        }
    }
}

  