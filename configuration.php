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

class ODConfig {
        public $live_site = 'http://your-website';
	public $list_limit = 30;
	public $access = 1;
	public $dbtype = 'mysqli';
	public $host = 'localhost';
	public $user = 'your-database-user';
	public $password = 'your-database-password';
	public $db = 'your-database-name';
        public $port = '3306';
	public $theme = 'proton';
	public $log_path = '/path/to/your/site/logs';
	public $tmp_path = '/path/to/your/site/tmp';
        public $top_domains = 10;
        public $ssl = [];
        /*
         * public $ssl = [
         * 'enable' => true,
         * 'verify_server_cert' => false,
         * 'key' => '/path/to/the/key',
         * 'cert' => '/path/to/the/cert',
         * 'ca' => '/path/to/the/CA cert',
         * 'capath' => '/path/to/the/CA certs',
         * 'cipher' => 'list of cyphers',
         * ]
        *           
         */
}

