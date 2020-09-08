<?php

declare(strict_types = 1);

namespace Inpsyde\Lib\WpEngine;

use Inpsyde\Lib\WpEngine\Bootstrap;

/**
 * Page class, provides page logic by being extended
 *
 * @author João Vieira
 * @license MIT
 */
class Page
{
    
    protected $plugin = null;
    
    private $runHook = 'parse_query';
    
    /**
     * Initializes a new page and assigns the main plugin class
     * @param  Bootstrap $plugin Plugin main class
     * @method __construct
     */
    public function __construct(Bootstrap &$plugin)
    {
        //Set bootstrap
        
        $this->plugin = $plugin;
        
        //Initializes
        
        $this->initialize();
    }
    
    /**
     * Initializes the plugin and registers hooks
     * @method initialize
     * @return bool
     */
    public function initialize() : bool
    {
        //Register
        
        $this->register();
        
        return true;
    }
    
    /**
     * Runs page
     * @method run
     * @return bool
     */
    public function run() : bool
    {
        return true;
    }
    
    /**
     * Registers actions
     * @method register
     * @return bool
     */
    public function register() : bool
    {
        //Register the run method
        
        $this->registerAction($this->runHook, 'run');
        
        return true;
    }
    
    /**
     * Renders view
     * @method render
     * @return bool
     */
    public function render() : bool
    {
        return true;
    }
    
    /**
     * Register Action
     * @method registerAction
     * @return bool
     */
    public function registerAction(String $name, String $method) : bool
    {
        add_action($name, [$this, $method]);
        
        return true;
    }
    
    /**
     * Register Filter
     * @method registerAction
     * @return bool
     */
    public function registerFilter(String $name, String $method) : bool
    {
        add_filter($name, [$this, $method]);
        
        return true;
    }
}
