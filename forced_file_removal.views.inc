<?php

/**
 * Implements hook_views_data_alter().
 */
function forced_file_removal_views_data_alter(array &$data) {
  if (!empty($data['file_managed'])) {
    $data['file_managed']['forced_file_removal'] = [
      'title' => t('Forced File Removal Ops'),
      'help'  => t('Delete file operation. For emergency use only. When you need to forcefully remove file for any reason.'),
      'field' => [
        'title' => t('Forced File Removal Ops'),
        'help'  => t('Delete file operation. For emergency use only'),
        'id'    => 'forced_file_removal',
      ],
    ];
  }
}
