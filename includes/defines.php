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
defined('_ODEXEC') or die;

// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, OD_BASE);

// Defines.
define('OD_ROOT', implode(DIRECTORY_SEPARATOR, $parts));
define('OD_SITE', OD_ROOT);
define('OD_CONFIGURATION', OD_ROOT);
define('OD_INCLUDES', OD_ROOT . DIRECTORY_SEPARATOR . 'includes');
define('OD_MODELS', OD_ROOT . DIRECTORY_SEPARATOR . 'models');
define('OD_STATIC', OD_ROOT . DIRECTORY_SEPARATOR . 'includes');
define('OD_THEMES', OD_BASE . DIRECTORY_SEPARATOR . 'templates');
define('OD_CACHE', OD_BASE . DIRECTORY_SEPARATOR . 'cache');
define('OD_SEF', OD_BASE . DIRECTORY_SEPARATOR . 'sef');

// Erros
define('ERR_NONE', 0);
define('ERR_INVALID_TOKEN', 500);

// Security
define('OD_USER', 'user');
define('OD_ADMIN', 'admin');
define('IV_KEY', '8w)kz^r71Z^V]*X');

//Policy to enforce
define('POLICY_UNKNOWN', 14);
define('POLICY_PASS', 15);
define('POLICY_REJECT', 16);
define('POLICY_QUARANTINE', 17);
define('POLICY_NONE', 18);

//SPF&DKIM Evaluation: 0 = pass, 2 = fail, 6 = none, -1 = not evaluated
define('EVAL_PASS', 0);
define('EVAL_FAIL', 2);
define('EVAL_NONE', 6);
define('EVAL_NOT_DONE', -1);

//SPF&DKIM Alignment set or not
define('ALIGNMENT_SET', 4);
define('ALIGNMENT_UNSET', 5);

//SPF&DKIM Alignment rule 
define('ALIGNMENT_RULE_RELAXED', 114);
define('ALIGNMENT_RULE_STRICT', 115);

//ARC Evaluation
define('ARC_PASS', 0);
define('ARC_FAIL', 2);

// Blank image
define('VPN_BLANK', 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=');
