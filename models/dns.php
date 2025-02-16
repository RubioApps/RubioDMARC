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

use RubioDMARC\Framework\Request;
use RubioDMARC\Framework\Language\Text;

class modelDNS extends Model
{

    public function display()
    {
        $this->page->title      = Text::_('DNS');
        $this->page->data       = $this->_data();

        $format = Request::getString('format', 'xhtml', 'GET');

        if ($format === 'json') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->page->data);
            die();
        }

        //parent::display();
    }

    protected function _data()
    {
        $host = Request::getString('host', '', 'GET');
        if ($host) {
            $dns = dns_get_record('_dmarc.' . $host, DNS_TXT);
            foreach ($dns as $f) {
                if (isset($f['txt'])) {
                    $this->data = $f;
                    return $f;
                }
            }
        }
        return $this->data;
    }
}
