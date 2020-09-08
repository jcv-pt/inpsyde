<?php

declare(strict_types = 1);
 
namespace Inpsyde\Core;

use Inpsyde\Lib\WpEngine\Page;
use Inpsyde\Lib\WpEngine\Form;
use Inpsyde\Lib\WpEngine\Template;
use Inpsyde\Lib\WpEngine\Path;
use Inpsyde\Lib\WpEngine\Cache;

/**
 * API page class, provides api data from the external endpoint and serves it via json response
 *
 * @author João Vieira
 * @license MIT
 */
class Api extends Page
{
    
    /**
     * Registers actions
     *
     * @method register
     * @return Bool
     */
    public function register() : bool
    {
        //Register run
        
        parent::register();
        
        //Register rewrite
        
        $this->registerAction('init', 'rewriteAction');
        
        //Register query vars
        
        $this->registerAction('query_vars', 'queryAction');
        
        return true;
    }
    
    /**
     * Process Api request and serves it as json
     *
     * @method run
     * @return Bool
     */
    public function run() : bool
    {
        //Check if we are on page
        
        if ((int)get_query_var('inpsyde_api') !== 1) {
            return true;
        }
        
        //Get Api Settings
        
        $settings = get_option('inpsyde_settings');
        
        $this->Api = (object)[
            'url' => (isset($settings['api_endpoint']) ? $settings['api_endpoint'] : ''),
            'cache' => [
                'enabled' => (isset($settings['cache_enabled']) ? (bool) $settings['cache_enabled'] : false),
                'expires' => (isset($settings['cache_expire']) ? (int) $settings['cache_expire'] : 1),
            ],
        ];

        //Init Cache

        $this->Cache = new Cache($this->Api->cache['enabled'], $this->Api->cache['expires']);
            
        //Set response
        
        $response = (object)[
            'status' => 'ok',
            'msg' => null,
            'data' => null,
        ];
        
        try {
            //Check model
            
            if (get_query_var('model') === '') {
                throw new \Exception('Model is not defined');
            }
            
            if (get_query_var('model') !== '') {
                $model = $this->normalize(get_query_var('model'));
            }
            
            //Check action
            
            if (get_query_var('action') === '') {
                throw new \Exception('Action is not defined');
            }
            
            if (get_query_var('action') !== '') {
                $action = $this->normalize(get_query_var('action'));
            }
            
            //Check if api model exists
            
            $path = Path::get('Plugin:Inpsyde/Core/Api/'.$model.'.php');

            if (!file_exists($path)) {
                throw new \Exception('Model not found');
            }
            
            //Initialize class
            
            $className = 'Inpsyde\\Core\\Api\\'.$model;
            
            $model = new $className($this);
        
            //Execute
    
            $model->{$action}($response);
        } catch (\Exception $exception) {
            //Patch response
            
            $response->status = 'error';
            $response->msg = $exception->getMessage();
        }
        
        //Set headers
        
        header('Content-Type: application/json');
        
        //Set response
        
        echo json_encode($response);
        
        //Stop execution;
        
        exit(1);
    }
    
    /**
     * Adds rewrite rules to wordpress
     *
     * @method rewriteAction
     * @return Bool
     */
    public function rewriteAction() : bool
    {
        //Set api endpoint
        
        add_rewrite_rule('^inpsyde/api$', 'index.php?inpsyde_api=1', 'top');
        add_rewrite_rule(
            '^inpsyde/api/([a-zA-Z0-9\_\-]+)/([a-zA-Z0-9\_\-]+)$',
            'index.php?inpsyde_api=1&model=$matches[1]&action=$matches[2]',
            'top'
        );
        
        //flush rules
        
        flush_rewrite_rules();
        
        return true;
    }
    
    /**
     * Adds query variables into wordpress whitelist
     *
     * @method queryAction
     * @return Array
     */
    public function queryAction(Array $vars) : array
    {
        //Add vars
        
        $vars[] = 'inpsyde_api';
        $vars[] = 'model';
        $vars[] = 'action';
        $vars[] = 'id';
        
        return $vars;
    }
    
    /**
     * Converts strings to camelcase
     *
     * @method normalize
     * @return String
     */
    private function normalize(String $name) : String
    {
        $name = str_replace('-', '_', $name);
        
        return str_replace('_', '', ucwords($name, '_'));
    }
}
