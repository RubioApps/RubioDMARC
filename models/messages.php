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

class modelMessages extends Model
{

    public function display()
    {
        $this->page->title      = Text::_('MESSAGES');
        $this->page->data       = $this->_data();
        $this->page->pagination = $this->_pagination();

        if ($this->page->data) {
            foreach ($this->page->data as $row) {
                Helpers::getDisposition($row);
                Helpers::getPolicy($row);
                Helpers::getAlignmentSet($row);
                Helpers::getARCEvaluation($row);
            }
        }

        parent::display();
    }

    protected function _data()
    {
        $domain = Request::getString('domain', '', 'GET');        
        $dkim   = Request::getInt('dkim', 0, 'GET');
        $spf    = Request::getInt('spf', 0, 'GET');
        $policy = Request::getInt('policy', 0 , 'GET');
        $period = Request::getString('period', 90, 'GET');  

        $filter = [];
        if ($domain !== '')
            $filter[] = "d.name LIKE '%" . $this->database->escape($domain) . "%'";

        if ($policy !== 0)
            $filter[] = "m.policy = " . (int) $policy;

        if ($dkim !== 0)
            $filter[] = "m.align_dkim = " . (int) $dkim;

        if ($spf !== 0)
            $filter[] = "m.align_spf = " . (int) $spf;


        $sql = "SELECT m.*, a.addr , e.name AS eval_domain  , d.name as from_domain , r.name AS reporter "
            . " FROM messages AS m , ipaddr AS a , domains AS e , domains AS d  , reporters AS r"
            . " WHERE m.from_domain = d.id "
            . " AND m.policy_domain = e.id "
            . " AND m.ip = a.id"
            . " AND m.reporter = r.id "
            . " AND m.date BETWEEN (NOW() - INTERVAL " . $period . " DAY) AND NOW() "
            . (count($filter) > 0 ? " AND " . join(" AND ", $filter) : "")
            . " ORDER BY m.date DESC";
        //die($sql);
        $this->data = $this->database->loadRows($sql);
        return $this->data;
    }
}
