<?php

namespace Drupal\forced_file_removal\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Url;

/**
 * Remove files
 *
 * @ViewsField("forced_file_removal")
 */
class RemoveFilesOp extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    if ($fid = $values->fid) {
      $links = [
        'delete' => [
          'title' => $this->t('Delete'),
          'url'   => Url::fromRoute('forced_file_removal.delete', ['fid' => $fid]),
        ],
      ];
      return [
        '#type'  => 'operations',
        '#links' => $links,
      ];
    }
  }

}
