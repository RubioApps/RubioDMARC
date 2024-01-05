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
define('_ODEXEC', 1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Saves the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

define('OD_BASE', dirname(__FILE__));
require_once OD_BASE . '/includes/defines.php';

// Load Factory
require_once OD_INCLUDES . '/factory.php';
$factory    = new OpenDMARC\Framework\Factory();

// Get configuration and locale
$config     = $factory->getConfig();
$language   = $factory->getLanguage();

// Create database connection
$options = [
    'host' => $config->host,
    'port' => $config->port,
    'user' => $config->user,
    'password' => $config->password,
    'database' => $config->db,
    ];
$database = $factory->getDatabase($options);

// Get the parsed URI query
if (isset($_GET["offset"]) && is_numeric($_GET["offset"])){
    $offset = $_GET["offset"];
} else {
    $offset = 0;
}

if (isset($_GET["limit"]) && is_numeric($_GET["limit"])){
    $limit = $_GET["limit"];
} else {
    $limit = $config->list_limit ;
}

// Set Model
OpenDMARC\Framework\Model::setDatabase($database);

if (isset($_GET["format"])){
    $format = $_GET["format"];
} else {
    $format = "xhtml";
}

switch($format)
{
    case "xhtml":
        require_once OD_THEMES . DIRECTORY_SEPARATOR . $config->theme . DIRECTORY_SEPARATOR . 'index.php';
        break;
    case "json":
        require_once $factory->getPage();
        break;
    default:
        /* TODO */
}
