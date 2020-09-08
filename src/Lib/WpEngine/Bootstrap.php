<?php

declare(strict_types = 1);

namespace Inpsyde\Lib\WpEngine;

use Inpsyde\Lib\WpEngine\Path;

/**
 * Bootstrap class, dispatcher for plugin
 *
 * @author João Vieira
 * @license MIT
 */
class Bootstrap
{
    
    private $name = null;
    private $vendor = null;
    private $uid = null;
    private $paths = null;
    
    private $libs = null;
    
    /**
     * Initializes the plugin and registers hooks
     * @param  string $uid The plugin slug
     * @method __construct
     */
    public function __construct(String $uid)
    {
        //Init parent
        
        $this->uid = strtolower($uid);
        
        $nameBits = explode('/', $this->uid);
        
        $this->vendor = current($nameBits);
        $this->name = end($nameBits);

        $this->paths = (object)[
            'file' => Path::get('Plugin:'.$this->name.'/.'.$this->name.'.php'),
        ];
        
        $this->libs = (object)[
            'collection' => [],
            'queue' => [],
            'load' => [],
        ];
    }
    
    /**
     * Initializes the plugin and registers hooks
     * @method initialize
     * @return boolean
     */
    public function initialize() : bool
    {
        // Plugin uninstall hook
        
        register_uninstall_hook($this->paths->file, [$this->name.'\Core\Plugin', 'uninstall']);

        // Plugin activation/deactivation hooks

        register_activation_hook($this->paths->file, [$this, 'activate']);
        register_deactivation_hook($this->paths->file, [$this, 'deactivate']);
        
        //Check if is active

        if (!in_array($this->uid.'.php', get_option('active_plugins'), true)) {
            return true;
        }
        
        //Runs plugin
        
        $this->run();
        
        //Enqueue libs
        
        $this->enqueue();
        
        return true;
    }
    
    /**
     * Runs plugin
     * @method run
     * @return boolean
     */
    public function run() : bool
    {
        return true;
    }
    
    /**
     * Performs plugin uninstall routine
     * @method uninstall
     * @return boolean
     */
    public static function uninstall() : bool
    {
        return true;
    }
    
    /**
     * Performs activation / init routine
     * @method activate
     * @return boolean
     */
    public function activate() : bool
    {
        return true;
    }
    
    /**
     * Performs plugin deactivate routine
     * @method deactivate
     * @return boolean
     */
    public function deactivate() : bool
    {
        return true;
    }
    
    /**
     * Register Setting
     * @param  string $group The setting group
     * @param  String $value The data
     * @method registerSetting
     * @return boolean
     */
    public function registerSetting(String $group, String $value) : bool
    {
        register_setting($group, $value);
        
        return true;
    }
    
    /**
     * Register Action with this class
     * @param  string $name The action hook
     * @param  string $method The method name
     * @method registerAction
     * @return boolean
     */
    public function registerAction(String $name, String $method) : bool
    {
        add_action($name, [$this, $method]);
        
        return true;
    }

    /**
     * Register a libs
     * @param  string $name The lib name
     * @param  array $files An array of string with file paths
     * @method registerLib
     * @return boolean
     */
    public function registerLib(String $name, Array $files) : bool
    {
        $this->libs->collection[$name] = $files;
        
        return true;
    }
    
    /**
     * Loads a specific lib
     * @param  string $name The lib name
     * @param  string $domain Weather frontend or backend
     * @method loadLib
     * @return boolean
     */
    public function loadLib(String $name, String $domain) : bool
    {
        $this->libs->queue[$name] = (object)[
            'name' => $name,
            'domain' => $domain,
        ];
        
        return true;
    }
    
    /**
     * Enqueue method to register libs to front and back end
     * @method enqueue
     * @return boolean
     */
    public function enqueue() : bool
    {
        foreach ($this->libs->queue as $name => &$lib) {
            if ($lib->domain === 'all' || $lib->domain === 'backend') {
                $this->registerAction('admin_enqueue_scripts', 'enqueueBackend');
                
                break;
            }
        }
            
        foreach ($this->libs->queue as $name => &$lib) {
            if ($lib->domain === 'all' || $lib->domain === 'frontend') {
                $this->registerAction('wp_enqueue_scripts', 'enqueueFrontend');
                
                break;
            }
        }

        return true;
    }
    
    /**
     * Enqueues lib files to wp frontend
     * @method enqueueBackend
     * @return boolean
     */
    public function enqueueFrontend() : bool
    {
        foreach ($this->libs->queue as $name => &$lib) {
            if ($lib->domain === 'all' || $lib->domain === 'frontend') {
                $this->enqueueLib($name);
            }
        }
        
        return true;
    }
    
    /**
     * Enqueues lib files to wp backend
     * @method enqueueBackend
     * @return boolean
     */
    public function enqueueBackend() : bool
    {
        foreach ($this->libs->queue as $name => &$lib) {
            if ($lib->domain === 'all' || $lib->domain === 'backend') {
                $this->enqueueLib($name);
            }
        }
        
        return true;
    }
    
    /**
     * Registers lib files to wp
     * @param  string $name The lib name
     * @method enqueueLib
     * @return boolean
     */
    public function enqueueLib(String $name) : bool
    {
        //Check if its registered
        
        if (!isset($this->libs->collection[$name])) {
            throw new \Exception('Lib '.$name.' is not registered');
        }
        
        //Check if has been loaded
        
        if (in_array($name, $this->libs->load, true)) {
            return true;
        }

        //Set lib
        
        $lib = $this->libs->collection[$name];
        
        //Set file counter
        
        $counter = 0;
        
        foreach ($lib as $type => $files) {
            //Load scripts
            
            if ($type === 'js') {
                $this->loadScript($name, $files, $counter);
            }

            //Load styles

            if ($type === 'css') {
                $this->loadStyle($name, $files, $counter);
            }
            
            //Load css

            if ($type === 'lib') {
                $this->enqueueLib($file);
            }
            
            $counter++;
        }
        
        //Set as loaded
        
        $this->libs->load[] = $name;
        
        return true;
    }
    
    /**
     * Get paths
     * @method paths
     * @return boolean
     */
    public function paths() : array
    {
        return $this->paths;
    }
    
    /**
     * Get registered libs
     * @method libs
     * @return boolean
     */
    public function libs() : array
    {
        return $this->paths;
    }
    
    /**
     * Converts file to object
     * @param  array $file A string array containing the lib files
     * @method normalizeLib
     * @return stdClass object
     */
    private function normalizeLib(Array $file) : \stdClass
    {
        //Declare default object
       
        $fileOpts = (object)[
            'path' => null,
            'dependencies' => [],
            'version' => null,
        ];
        
        //Patch
        
        if (isset($file[0])) {
            $fileOpts->path = $file[0];
        }
        
        if (isset($file[1])) {
            $fileOpts->dependencies = $file[1];
        }
        
        if (isset($file[2])) {
            $fileOpts->version = $file[2];
        }
        
        return $fileOpts;
    }
    
    /**
     * Loads script
     * @method loadScript
     * @param  string $name The lib name
     * @param  array $files An array of strings with the lib files
     * @param  int $extUid The lib external uid
     * @return boolean
     */
    private function loadStyle(String $name, Array $files, Int $extUid = 0) : bool
    {
        $counter = 0;
        
        foreach ($files as $file) {
            //Normalize
            
            $fileOpts = $this->normalizeLib((is_string($file) ? [$file] :  $file));
            
            //Check if matches path slugs
            
            $url = Path::url($fileOpts->path, false);
            
            if ($url) {
                $fileOpts->path = $url;
            }

            //Set file uid
            
            $uid = $name.'-'.$extUid.'-'.$counter;
        
            wp_register_style($uid, $fileOpts->path, $fileOpts->dependencies, $fileOpts->version, 'all');
            wp_enqueue_style($uid);

            //Increment counter
            
            $counter++;
        }
        
        return true;
    }
    
    /**
     * Loads style
     * @method loadScript
     * @param  string $name The lib name
     * @param  array $files An array of strings with the lib files
     * @param  int $extUid The lib external uid
     * @return boolean
     */
    private function loadScript(String $name, Array $files, Int $extUid = 0) : bool
    {
        $counter = 0;
        
        foreach ($files as $file) {
            //Normalize
            
            $fileOpts = $this->normalizeLib((is_string($file) ? [$file] :  $file));
            
            //Check if matches path slugs
            
            $url = Path::url($fileOpts->path, false);
            
            if ($url) {
                $fileOpts->path = $url;
            }

            //Set file uid
        
            $uid = $name.'-'.$extUid.'-'.$counter;
        
            wp_register_script($uid, $fileOpts->path, $fileOpts->dependencies, $fileOpts->version, true);
            wp_enqueue_script($uid);

            //Increment counter
            
            $counter++;
        }
        
        return true;
    }
}
