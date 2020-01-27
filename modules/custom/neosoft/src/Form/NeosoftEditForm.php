<?php

namespace Drupal\neosoft\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This is a simple form for managing neosoft information details.
 * {@inheritdoc}
 */
class NeosoftEditForm extends FormBase
{

    /**
     * Here we return formId to drupal core for understading.
     */
    public function getFormId()
    {
        return 'neosoft_edit_form';
    }

    /**
     * This actaully building form that are shown on front end.
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $cId = $_GET['id'];

        $database = \Drupal::database();
        $query = $database->query("SELECT * FROM {neosoft} where cid = $cId");
        $result = $query->fetchAssoc($cId);

        /*print($cId);
        echo $result['status'];
        print("<pre>".print_r($result,true)."</pre>"); die('stop'); */

        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Title'),
            '#default_value' => $result['title'],
            '#required' => true,
            '#maxlength' => 255,
        ];
        $form['short_desc'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Short Description'),
            '#default_value' => $result['short_desc'],
            '#required' => true,
        ];

        $form['long_desc'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Long Description'),
            '#default_value' => $result['long_desc'],
            '#required' => true,
        ];
        $form['content_image'] = array(
            '#type' => 'managed_file',
            '#title' => t('Content image'),
            '#default_value' =>!empty($values['img']) ? $values['img'] : '',
            '#description' => t("Image should be in JPG, PNG, GIF format only."),
            '#upload_validators' => array(
                'file_validate_extensions' => array('gif png jpg'),
            ),
            '#upload_location' => 'public://neosoft-img',
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

        $form['cancel'] = array(
            '#type' => 'submit',
            //'#submit' => array('::previousForm'), //this works too
            '#submit' => array([$this, 'cancelForm']),
            '#value' => $this->t('Cancel'),
            '#limit_validation_errors' => array(), //no validation for back button
        );

        return $form;
    }

    /**
     * This is neosoft form custom validation.
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // Add length validation to first name.
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
     * Save data into database cuastom table
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $cId = $_GET['id'];
        $short_desc = $form_state->getValue('short_desc');
        $long_desc = $form_state->getValue('long_desc');
        $img = $form_state->getValue('content_image');

        if(!empty($img)) {
            $img = $form_state->getValue('content_image');
            $image = \Drupal\file\Entity\File::load($img[0]);
            $path = file_create_url($image->getFileUri());
            $image_url = parse_url ($path, PHP_URL_PATH);
         }else{
            $result = \Drupal::database()->select('neosoft','n')
            ->fields('n',array('img'))
            ->condition('n.cid', $cId)
            ->execute()->fetchAssoc();;
            $image_url = $result['img'];
         }

        db_update('neosoft')
            ->fields(array(
                'title' => $form_state->getValue('title'),
                'short_desc' => strip_tags($short_desc['value']),
                'long_desc' => strip_tags($long_desc['value']),
                'img' => $image_url,
                'status' => $form_state->getValue('status'),
                'last_updated' => date("Y-m-d H:i:s", time()),
            ))
            ->condition('cid', $cId)
            ->execute();
        //clear views cache
        drupal_flush_all_caches();

        $this->messenger()->addStatus('Record have been updated successfully.', TRUE);
        $response = new RedirectResponse("../neosoft/list");
        $response->send();
    }

    public static function cancelForm(array &$form, FormStateInterface $form_state)
    {
        $response = new RedirectResponse("../neosoft/list");
        $response->send();
    }

}