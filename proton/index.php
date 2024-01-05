<!DOCTYPE html>
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
defined('_ODEXEC') or die;

use OpenDMARC\Framework\Language\Text; 
?>
<html class="no-js withBanner" lang="<?= $language->getTag(); ?>"<?= ($language->isRtl() ? ' dir="rtl"' : ''); ?>>
    <head>
	<noscript>
            <style>
                html[data-bgs="gainsboro"] { background-color: #d6d6d6; } 
                html[data-bgs="nightRider"] { background-color: #1a1c20; } 
                html[data-bgs="nightRider"] div[data-noscript] { color: #979ba080; } 
                html[data-slider-fixed='1'] { margin-right: 0 !important; } 
                body > div[data-noscript] ~ * { display: none !important; } 
                div[data-noscript] { visibility: hidden; animation: 2s noscript-fadein; animation-delay: 1s; text-align: center; animation-fill-mode: forwards; } 
                @keyframes noscript-fadein { 0% { opacity: 0; } 100% { visibility: visible; opacity: 1;}}
            </style>
            <div data-noscript>
                <div class="fa fa-3x fa-exclamation-triangle margined-top-20 text-danger"></div>
                <h2>JavaScript is disabled</h2>
                <p>Please enable javascript and refresh the page</p>
            </div> 
        </noscript>
	<meta charset="utf-8">
 	<meta name="msapplication-TileColor" content="#2d5d9d">
 	<meta name="msapplication-TileImage" content="<?= $factory->getAssets() ;?>/favicons/mstile-144x144.png">
 	<meta name="theme-color" content="#2d5d9d">    
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= $factory->getAssets() ;?>/favicons/favicon-16x16.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= $factory->getAssets() ;?>/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="48x48" href="<?= $factory->getAssets() ;?>/favicons/favicon-48x48.png">
	<link rel="apple-touch-icon" sizes="57x57" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= $factory->getAssets() ;?>/favicons/apple-touch-icon-180x180.png">
        <title><?= Text::_('DASHBOARD'); ?></title>     
        <link rel="stylesheet" href="<?= $factory->getAssets() ;?>/protonvpn.css" />     
  	<style type="text/css">
            body { background-color: rgb(38, 42, 51); }
            .spacer { display: block; margin-top:30px;}
            .block-info-standard { color: #fff; }
            .connected{ color: lightgreen; }
            .disconnected{ color: red; }
            .logo-link{ background: url("<?= $factory->getAssets() ;?>/images/opendmarc.png") 0 0 no-repeat; width: 153px; display: block;}    
	</style>        
        <script src="<?= $factory->getAssets() ;?>/jquery-1.11.1.min.js"></script>      
        <script src="<?= $factory->getAssets() ;?>/chart.js"></script>                  
        <script type="text/javascript">   
            $(document).ready(function(){     
                $('#hl-selector').change(function(){
                $('#hl-form').submit();  
                });
            });
        </script>
    </head>    
    <body class="isDarkMode is-comfortable">       
        <div class="content-container flex flex-column flex-nowrap">
            <div class="content flex-item-fluid flex flex-column flex-nowrap">
                <header class="header flex flex-nowrap">
                    <div class="logo-container">
                        <a class="logo-link nodecoration" href="/opendmarc"></a>                         
                    </div>                   
                    <?php  require_once $factory->getPage('menu'); ?>                         
                </header>
		<div class="flex flex-item-fluid flex-nowrap">     
                    <div class="flex flex-column flex-nowrap flex-item-fluid">
        		<main class="flex-item-fluid relative main-area--withToolbar">       
                            <div class="flex flex-spacebetween flex-items-center pl1 pr1">                  	                	
                                <section class="container-section-sticky--fullwidth p0 mb1">       					                                                  
                                    <?php  require_once $factory->getPage(); ?>                                
                                </section>
                            </div>
        		</main>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>                            
