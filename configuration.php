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

namespace RubioDMARC\Framework;

defined('_ODEXEC') or die;

class ODConfig
{
        public $sitename        = 'Website title';
        public $live_site       = 'https://yourwebsite.com';
        public $log_path        = '/to/your/path/log';
        public $tmp_path        = '/to/your/path/tmp';
        public $debug           = false;
        public $use_sef         = true;
        public $use_autolog     = false;
        public $password        = '{MD5 hash of the plain admin password}';
        public $key             = '{SHA256 secret key for security purposes}';
        public $list_limit      = 10;
        public $database        = [
                'type' => 'mysqli',
                'host' => 'localhost',
                'port' => '3306',
                'user' => '{database user}',
                'password' => '{database password}',
                'database' => '{database name}',
        ];
        public $menu = ['home', 'messages', 'hosts'];
        public $theme = 'default';
        public $top_domains = 5;
        public $links = [
                'DMARC Checker'       => 'https://easydmarc.com/tools/dmarc-lookup',
                'SPF Checker'         => 'https://easydmarc.com/tools/spf-lookup',
                'DKIM Validator'      => 'https://dkimvalidator.com/',
        ];
}
