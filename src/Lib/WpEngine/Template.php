<?php

declare(strict_types = 1);

namespace Inpsyde\Lib\WpEngine;

use Inpsyde\Lib\WpEngine\Path;

/**
 * Template class, provides templating by rendering html
 *
 * @author João Vieira
 * @license MIT
 */
class Template
{
    
    private static $extend = null;
    
    /**
     * Renders a template file into a string
     * @param  string $path Slug path string
     * @param  array $params Array of data containing all variables to made available to template
     * @method render
     * @throws \Exception
     * @return bool
     */
    public static function render(String $path, Array $params = []) : bool
    {
        //Compile & check if file exists

        $file = Path::get($path);
        
        if (!file_exists($file)) {
            throw new \Exception('Template file "'.$file.'" could not be loaded');
        }
        
        //Make values in the associative array easier to access by extracting them

        foreach ($params as $key => $data) {
            ${$key} = $data;
        }
        
        //Include file
        
        require $file;
        
        return true;
    }
}
