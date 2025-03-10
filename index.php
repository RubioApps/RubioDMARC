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
 | This file forms part of the RubioDMARC software.                        |
 |                                                                         |
 | If you wish to use this file in another project or create a modified    |
 | version that will not be part of the RubioDMARC Software, you           |
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
define('_ODEXEC', 1);

define('OD_BASE', dirname(__FILE__));
require_once OD_BASE . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'defines.php';

// Load Factory
require_once OD_INCLUDES . DIRECTORY_SEPARATOR . 'factory.php';
$factory    = new RubioDMARC\Framework\Factory();

// Initialize
$factory->initialize();

// Get configuration and locale
$config     = $factory->getConfig();

// Get the language
$language   = $factory->getLanguage();

// Get the router
$router     = $factory->getRouter();

// Get the page
$page = $factory->getPage();

// JS Bridge
$factory->jsBridge();

// Dispatch
require_once $router->dispatch();

// Finalize
$factory->finalize();
