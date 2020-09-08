<?php

declare(strict_types = 1);

namespace Inpsyde\Core;

use Inpsyde\Lib\WpEngine\Page;
use Inpsyde\Lib\WpEngine\Form;
use Inpsyde\Lib\WpEngine\Template;

/**
 * Settings page class, responsible for creating a plugin settings
 * menu entry in the backend and provide configuration settings
 *
 * @author João Vieira
 * @license MIT
 */
class Settings extends Page
{
    
    /**
     * Registers actions for the settings page
     *
     * @method register
     * @return Bool
     */
    public function register() : bool
    {
        $this->registerAction('admin_menu', 'createMenu');
        $this->registerAction('admin_init', 'createMenuSettings');
        
        return true;
    }
    
    /**
     * Creates the menu entry on the admin panel
     *
     * @method createMenu
     * @return Bool
     */
    public function createMenu() : bool
    {
        add_options_page(
            __('Inpsyde'),
            __('Inpsyde'),
            'manage_options',
            'inpsyde-plugin',
            [$this, 'render']
        );
        
        return true;
    }
    
    /**
     * Creates the settings form in the configuration page
     *
     * @method createMenuSettings
     * @return Bool
     */
    public function createMenuSettings() : bool
    {
        //Create New form
        
        $form = new Form('inpsyde', 'inpsyde_settings');
        
        //Add section
        
        $this->createFormSection($form);
        
        //Add fields
        
        $this->createFormFields($form);
        
        return true;
    }

    /**
     * Render the settings page html
     *
     * @method render
     * @return bool
     */
    public function render() : bool
    {
        Template::render('Plugin:Inpsyde/Templates/Settings/form.php');

        return true;
    }
    
    /**
     * Add section to form
     *
     * @method createFormSection
     * @param Inpsyde\Lib\WpEngine\Form instance of the form
     * @return bool
     */
    private function createFormSection(Form &$form) : bool
    {
        //Add section

        $form->addSection('inpsyde_settings', [
            'label' => __('Endpoint Settings'),
            'render' => function () {?>
                <p><?= esc_html(__('Here you can set all the options for the plugin endpoint'));?></p>
                <?php
            },
        ]);
        
        return true;
    }
    
    /**
     * Adds fields to form
     *
     * @method createFormFields
     * @param Inpsyde\Lib\WpEngine\Form instance of the form
     * @return bool
     */
    private function createFormFields(Form &$form) : bool
    {
        //Ads fields
        
        $form->addField('endpoint', [
            'label' => __('Endpoint Url'),
            'type' => 'text',
            'section' => 'inpsyde_settings',
            'validate' => function (String $input) : string {

                if (preg_match('/([a-z0-9\-\/]+)/m', $input, $matches) !== 1) {
                    return __('Endpoint url is not a valid URI');
                }
                
                //Check if starts with the following

                if (preg_match('/^\/?(login|wp-admin|inpsyde).*/', $input, $matches) !== 0) {
                    return __('Endpoint url is system reserved');
                }
                
                return '';
            },
            'style' => 'min-width:250px;',
            'note' => __('Only relative urls with chars (a-z,0-9,-,/)'),
        ]);
        
        $form->addField('api_endpoint', [
            'label' => __('Api Endpoint Url'),
            'type' => 'text',
            'section' => 'inpsyde_settings',
            'validate' => function (String $input) : string {

                if (!filter_var($input, FILTER_VALIDATE_URL)) {
                    return __('Api Endpoint url is not a valid URI');
                }
                
                return '';
            },
            'style' => 'min-width:250px;',
        ]);
        
        $form->addField('cache_enabled', [
            'label' => __('Cache Enable'),
            'type' => 'select',
            'section' => 'inpsyde_settings',
            'options' => [
                '1' => __('Yes'),
                '0' => __('No'),
            ],
        ]);
        
        $form->addField('cache_expire', [
            'label' => __('Cache Expires'),
            'type' => 'select',
            'section' => 'inpsyde_settings',
            'options' => [
                '1' => __('1 Minute'),
                '30' => __('30 Minutes'),
                '120' => __('2 Hours'),
            ],
        ]);
    
        return true;
    }
}
