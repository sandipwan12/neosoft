<?php
namespace Drupal\neosoft\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DeleteForm.
 *
 * @package Drupal\neosoft\Form
 */
class NeosoftDeleteForm extends ConfirmFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'delete_form';
    }

    public function getQuestion()
    {
        return t('Do you want to delete this information');
    }
    public function getCancelUrl()
    {
        return new Url('neosoft.list');
    }
    public function getDescription()
    {
        return t('Only do this if you are sure!');
    }
    /**
     * {@inheritdoc}
     */
    public function getConfirmText()
    {
        return t('Delete it!');
    }
    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return t('Cancel');
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $cid = null)
    {
        $this->id = $cid;
        return parent::buildForm($form, $form_state);
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $cid = $_GET['id'];
        $database = \Drupal::database();
        $res = db_delete('neosoft')
            ->condition('cid', $cid)
            ->execute();
        //clear views cache
        drupal_flush_all_caches();
        drupal_set_message("selected record deleted succesfully.");
        $response = new RedirectResponse("../neosoft/list");
        $response->send();
    }
}