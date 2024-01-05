<?php
/**
 +-------------------------------------------------------------------------+
 | RubioDMARC  - An OpenDMARC Webapp                                       |
 | Version 1.0.0                                                           |
 |                                                                         |
 | This program is free software: you can redistribute it and/or modify    |
 | it under the terms of the GNU General Public License as published by    |
 | the Free Software Foundation.                                           |
 |                                                                         |
 | This file forms part of the RubioTV software.                           |
 |                                                                         |
 | If you wish to use this file in another project or create a modified    |
 | version that will not be part of the RubioTV Software, you              |
 | may remove the exception above and use this source code under the       |
 | original version of the license.                                        |
 |                                                                         |
 | This program is distributed in the hope that it will be useful,         |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of          |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            |
 | GNU General Public License for more details.                            |
 |                                                                         |
 | You should have received a copy of the GNU General Public License       |
 | along with this program.  If not, see http://www.gnu.org/licenses/.     |
 |                                                                         |
 +-------------------------------------------------------------------------+
 | Author: Jaime Rubio <jaime@rubiogafsi.com>                              |
 +-------------------------------------------------------------------------+
*/
namespace OpenDMARC\Framework;

defined('_ODEXEC') or die;

// Pre-Load configuration. Don't remove the Output Buffering due to BOM issues  
ob_start();
require_once OD_CONFIGURATION . '/configuration.php';    
ob_end_clean();

require_once OD_INCLUDES . '/database.php';   
require_once OD_INCLUDES . '/language.php';
require_once OD_INCLUDES . '/pagination.php';
require_once OD_INCLUDES . '/helpers.php';
require_once OD_INCLUDES . '/model.php';
require_once OD_INCLUDES . '/text.php';

use OpenDMARC\Framework\Database;
//use OpenDMARC\Framework\Language;
            
class Factory
{

    protected static $database;    
    protected static $language;  
    protected static $locale;
    protected static $config;
    protected static $assets;
    protected static $task;
    protected static $page;
    protected static $theme;

    public static function getConfig()
    {
        if(!static::$config){  
            static::$config = new ODConfig();
        }                        
        return static::$config;        
    }   
    
    public static function getDatabase( $options = array() )
    {
        if(!static::$database){                     
            static::$database = new Database($options);
        }                
        
        return static::$database;        
    }

    public static function getLanguage( $lang = null )
    {
        if(!static::$language){                     
            static::$language= new Language($lang);
        }                
        
        return static::$language;        
    } 
    
    public static function getLangTag()
    {
        if(!static::$locale){ 
            $language = self::getLanguage();
            static::$locale = $language->getTag();
        }
        return static::$locale;
    }

    public static function getAssets()
    {        
        if(!static::$assets){  
            $config = self::getConfig();         
            static::$assets = $config->live_site . '/templates/' . $config->theme . '/assets';
        }                        
        return static::$assets;        
    }    

    public static function getTheme()
    {        
        if(!static::$theme){  
            $config = self::getConfig();         
            static::$theme = OD_THEMES . DIRECTORY_SEPARATOR . $config->theme;
        }                        
        return static::$theme;        
    }        

    public static function getTask()
    {
        if(!static::$task){                 
            if (isset($_GET["task"])){
                static::$task = $_GET["task"];
            } else {
                static::$task = 'dashboard';
            }
        }
        return static::$task;
    }
    
    
    public static function getPage( $pagename = null)
    {
        if(!$pagename)
        {                        
            if(!static::$page)
            {            
                $filename= ( self::getTheme() . DIRECTORY_SEPARATOR . self::getTask() . '.php');         
                if(file_exists($filename))
                {                
                    static::$page = $filename;
                } else {
                    // TODO: Redirect 404
                    static::$page = ( OD_THEMES . DIRECTORY_SEPARATOR . 'index.html');                 
                }            
                return static::$page;
            }            
        } else {
            $filename= ( self::getTheme() . DIRECTORY_SEPARATOR . $pagename . '.php');         

            if(file_exists($filename)){     
               return $filename;
            } else {
               return ( OD_THEMES . DIRECTORY_SEPARATOR . 'index.html');    
            }
        }
       
    }
    
}

