<?php

declare(strict_types = 1);

namespace Inpsyde\Lib\WpEngine;

/**
 * Path class, provides easy file and urls string paths from a slug
 *
 * @author João Vieira
 * @license MIT
 */
class Path
{
    
    private static $regex = '/(Plugin|Theme)\:([a-zA-z0-9\_\-]+)\/(\.?[a-zA-z0-9\_\-\.\/]+)/m';
    
    /**
     * Gets file full path from slug string
     * @param  string $path Slug path string
     * @param  bool $strict Weather to throw an exception if slug is not valid
     * @method get
     * @throws \Exception
     * @return string
     */
    public static function get(String $path, Bool $strict = true) : string
    {
        if (!defined('WP_CONTENT_DIR')) {
            throw new \Exception('WP_CONTENT_DIR is not defined, cannot determine path');
        }
        
        //Match pattern
        
        $matches = [];

        if (preg_match(self::$regex, $path, $matches) !== 1) {
            if ($strict === false) {
                return '';
            }
            
            throw new \Exception('Path "'.$path.'" is invalid and it could not be determined.');
        }
        
        //Build path
        
        $fullPath = WP_CONTENT_DIR;
        
        //Set lib type
        
        switch ($matches[1]) {
            case 'Plugin':
                $fullPath .= '/plugins';
                break;
            
            case 'Theme':
                $fullPath .= '/themes';
                break;
            
            default:
                throw new \Exception('Path "'.$path.'" is invalid lib type could not be determined.');
        }
        
        //Set lib namespace
        
        $fullPath .= strtolower('/'.$matches[2]);
        
        //Set dir
        
        if (substr($matches[3], 0, strlen('.')) === '.') {
            //Its absolute path from plugins
            
            $fullPath .= '/'.substr($matches[3], 1);
        }
        
        if (substr($matches[3], 0, strlen('.')) !== '.') {
            //Its relative to src in lib
            
            $fullPath .= '/src/'.$matches[3];
        }
        
        return $fullPath;
    }
    
    /**
     * Gets file relative url from slug string
     * @param  string $path Slug path string
     * @param  bool $strict Weather to throw an exception if slug is not valid
     * @method url
     * @throws \Exception
     * @return string
     */
    public static function url(String $path, Bool $strict = true) : string
    {
        //Match pattern
        
        $matches = [];

        if (preg_match(self::$regex, $path, $matches) !== 1) {
            if ($strict === false) {
                return '';
            }
            
            throw new \Exception('Path "'.$path.'" is invalid and it could not be determined.');
        }
        
        //Build path
        
        $fullPath = '';
        
        //Set lib type
        
        switch ($matches[1]) {
            case 'Plugin':
                $fullPath .= '/wp-content/plugins';
                break;
            
            case 'Theme':
                $fullPath .= '/wp-content/themes';
                break;
            
            default:
                throw new \Exception('Path "'.$path.'" is invalid lib type could not be determined.');
        }
        
        //Set lib namespace
        
        $fullPath .= strtolower('/'.$matches[2]);
        
        //Set dir
        
        if (substr($matches[3], 0, strlen('.')) === '.') {
            //Its absolute path from plugins
            
            $fullPath .= '/'.$matches[3];
        }
        
        if (substr($matches[3], 0, strlen('.')) !== '.') {
            //Its relative to src in lib
            
            $fullPath .= '/src/'.$matches[3];
        }
        
        return $fullPath;
    }
}
