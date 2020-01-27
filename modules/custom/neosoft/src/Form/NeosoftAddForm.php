<?php

namespace Drupal\neosoft\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * This is my simple add form for managing neosoft details usiong Drupal Form API.
 */
class NeosoftAddForm extends FormBase
{
    /**
     * Here we return formId to drupal core for understading.
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'neosoft_add_form';
    }

    /**
     * This actaully building form that are shown on front end.
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Title'),
            '#required' => true,
            '#maxlength' => 255,
        ];
        $form['short_desc'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Short Description'),
            '#required' => true,
        ];

        $form['long_desc'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Long Description'),
            '#required' => true,
        ];
        $form['content_image'] = array(
            '#type' => 'managed_file',
            '#title' => t('Content image'),
            '#description' => t("Image should be in JPG, PNG, GIF format only."),
            '#upload_validators' => array(
                'file_validate_extensions' => array('gif png jpg'),
            ),
            '#upload_location' => 'public://neosoft-img',
            '#required' => TRUE
          );
        $form['status'] = [
            '#type' => 'radios',
            '#title' => t('Neosoft Status'),
            '#default_value' => 'Active',
            '#options' => array('Active' => 'Active', 'Deactive' => 'Deactive'),
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#button_type' => 'primary',
        ];

        $form['cancel'] = [
            '#type' => 'button',
            '#value' => t('Cancel'),
            '#attributes' => array('onClick' => 'history.go(-1); return true;'),
        ];

        return $form;
    }

    /**
     * This is neosoft form custom validation.
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        /// Add length validation to first name.
        $title = $form_state->getValue('title');

        /**
         * Add chacter and length validation for first name.
         */

        if (!preg_match('/^[A-Za-z\s]{1,}[\.]{0,1}[A-Za-z\s]{0,255}$/', $title)) {
            $form_state->setErrorByName(
                'title',
                $this->t('Title must contain only Alphabets, Dots, Spaces and it must be between 3-255 character in length.')
            );
        }

    }

    /**
     * Save data into database custom table
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $short_desc = $form_state->getValue('short_desc');
        $long_desc = $form_state->getValue('long_desc');
        $img = $form_state->getValue('content_image');

        $image = \Drupal\file\Entity\File::load($img[0]);
        $path = file_create_url($image->getFileUri());
        $image_url = parse_url ($path, PHP_URL_PATH);
        //echo $image_url; die();

        /*
        foreach ($form_state->getValues() as $key => $value) {
            drupal_set_message($key . ': ' . $value);
          } */

        db_insert('neosoft')
            ->fields(array(
                'title' => $form_state->getValue('title'),
                'short_desc' => strip_tags($short_desc['value']),
                'long_desc' => strip_tags($long_desc['value']),
                'img' => $image_url,
                'status' => $form_state->getValue('status'),
                'last_updated' => date("Y-m-d H:i:s", time()),
            ))->execute();
        //clear views cache
        drupal_flush_all_caches();
        drupal_set_message("Record have been saved successfully.");
        $response = new RedirectResponse("../neosoft/list");
        $response->send();
    }

}
