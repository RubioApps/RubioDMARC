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

use RubioDMARC\Framework\Helpers;
use RubioDMARC\Framework\Language\Text;

class modelHosts extends Model
{

    public function display()
    {
        $this->page->title      = Text::_('REQUESTS');
        $this->page->data       = $this->_data();
        $this->page->pagination = $this->_pagination();

        foreach ($this->page->data as $row) {
            $row = Helpers::getAlignmentMode($row);
            $row = Helpers::getDomainPolicy($row);
            $row = Helpers::getSubdomainPolicy($row);
        }

        parent::display();
    }

    protected function _data()
    {
        $domain = Request::getString('domain', '', 'GET');        

        $filter = [];
        if ($domain !== '')
            $filter[] = "d.name LIKE '%" . $this->database->escape($domain) . "%'";

        $sql = "SELECT d.name AS requester , r.* "
            . " FROM requests AS r , domains AS d "
            . " WHERE r.domain = d.id"
            . (count($filter) > 0 ? " AND " . join(" AND ", $filter) : "")
            . " ORDER BY d.name ASC"
            ;
        $this->data = $this->database->loadRows($sql);
        return $this->data;
    }
}
