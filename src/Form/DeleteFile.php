<?php

namespace Drupal\forced_file_removal\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

class DeleteFile extends ConfirmFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'delete_file_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you would like to delete the file?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('view.files.page_1');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('IMPORTANT: If you delete the file without first deleting any references to it you might end up with broken links. This operation cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete File');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $fid = NULL) {
    $form_state->setStorage([
      'fid' => $fid,
    ]);
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $storage = $form_state->getStorage();
    $user = \Drupal::currentUser();
    if (!empty($storage['fid'])) {
      if ($filename = $this->removeFile($storage['fid'])) {
        drupal_set_message($this->t('File <em>@filename</em> successfully removed.', ['@filename' => $filename]));
      }
    }
    $form_state->setRedirect('view.files.page_1');
  }

  /**
   * File removal routine.
   * And keep track of who and when deleted a file.
   */
  protected function removeFile($fid) {
    $filename = '';
    if ($file = File::load($fid)) {
      $filename = $file->getFilename();
      $file->delete();
      db_insert('file_removal_history')
        ->fields([
          'fid'        => $file->id(),
          'file_uri'   => $file->getFileUri(),
          'uid'        => $user->id(),
          'username'   => $user->getAccountName(),
          'deleted_at' => REQUEST_TIME,
        ])
        ->execute();
    }
    return $filename;
  }

}
