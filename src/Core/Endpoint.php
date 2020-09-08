<?php

declare(strict_types = 1);

namespace Inpsyde\Core;

use Inpsyde\Lib\WpEngine\Page;
use Inpsyde\Lib\WpEngine\Form;
use Inpsyde\Lib\WpEngine\Template;
use Inpsyde\Lib\WpEngine\Path;

/**
 * Endpoint page class, responsible for deliver main view
 * for the user list on the url specified in the configuration settings
 *
 * @author João Vieira
 * @license MIT
 */
class Endpoint extends Page
{
    
    /**
     * Registers actions & filters for the endpoint page
     *
     * @method register
     */
    public function register() : bool
    {
        //Register run
        
        parent::register();
        
        //Register rewrite
        
        $this->registerAction('init', 'rewriteAction');
        
        //Register query vars
        
        $this->registerAction('query_vars', 'queryAction');

        //Register template
        
        $this->registerFilter('template_include', 'templateAction');
        
        return true;
    }
    
    /**
     * Registers CSS and JS dependencies and loads them to the frontend
     *
     * @method run
     */
    public function run() : bool
    {
        //Check if we are on page
        
        if ((int)get_query_var('inpsyde_enpoint') !== 1) {
            return true;
        }
        
        //Register & load js
        
        $this->plugin->registerLib('inpsyde_enpoint', [
            'js' => [
                'Plugin:Inpsyde/Assets/js/pages/endpoint.js',
            ],
            'css' => [
                'Plugin:Inpsyde/Assets/css/pages/endpoint.css',
                'Plugin:Inpsyde/Assets/libs/loader/loader.css',
            ],
        ]);
        
        $this->plugin->registerLib('loader', [
            'css' => [
                'Plugin:Inpsyde/Assets/libs/loader/loader.css',
            ],
        ]);
        
        $this->plugin->loadLib('inpsyde_enpoint', 'frontend');
        $this->plugin->loadLib('loader', 'frontend');
        
        return true;
    }
    
    /**
     * Adds rewrite rules to wordpress
     *
     * @method rewriteAction
     * @return bool
     */
    public function rewriteAction() : bool
    {
        //Get settings
        
        $settings = get_option('inpsyde_settings');
        
        if (!isset($settings['endpoint'])) {
            return true;
        }
        
        //Normalize url
        
        $url = trim($settings['endpoint'], '/');
        
        add_rewrite_rule('^'.$url.'$', 'index.php?inpsyde_enpoint=1', 'top');
        
        //flush rules
        
        flush_rewrite_rules();
        
        return true;
    }
    
    /**
     * Adds query variables into wordpress whitelist
     *
     * @method queryAction
     * @return Array $vars
     */
    public function queryAction(Array $vars) : array
    {
        //Add vars
        
        $vars[] = 'inpsyde_enpoint';
        
        return $vars;
    }

    /**
     * Renders the page template into a string
     *
     * @method templateAction
     * @return String $template
     */
    public function templateAction(String $template) : string
    {
        if (get_query_var('inpsyde_enpoint')) {
            $template = Path::get('Plugin:Inpsyde/Templates/Endpoint/table.php');
        }

        return $template;
    }
}
