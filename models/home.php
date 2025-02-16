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

class modelHome extends Model
{
    protected $period;

    public function display()
    {
        $this->period = Request::getInt('period', 30, 'GET');

        $this->page->title              = Text::_('HOME');
        $this->page->data               = $this->_data();
        parent::display();
    }


    public function _data()
    {
        $this->data = [];
        $this->data['aggregate']    = $this->_aggregate();
        $this->data['pivot']        = $this->_timeline();
        $this->data['domains']      = $this->_top_domains();
        $this->data['rejects']      = $this->_non_compliant_domains();

        return $this->data;
    }

    protected function _aggregate()
    {
        $sql = "SELECT policy , COUNT(*) AS cnt FROM messages GROUP BY policy";
        $rows = $this->database->loadRows($sql);
        foreach ($rows as $row) {
            $row = Helpers::getPolicy($row);
        }
        return $rows;
    }

    protected function _timeline()
    {

        $sql = "SELECT DATE_FORMAT(date,'%d-%m') AS mydate, "
            . " SUM(CASE WHEN policy = " . POLICY_PASS . "  THEN 1 ELSE 0 END) AS pass, "
            . " SUM(CASE WHEN policy =  " . POLICY_QUARANTINE . " THEN 1 ELSE 0 END) AS quarantine, "
            . " SUM(CASE WHEN policy IN (" . POLICY_REJECT . "," . POLICY_UNKNOWN . "," . POLICY_NONE . ")  THEN 1 ELSE 0 END) AS reject "
            . " FROM messages "
            . " GROUP BY mydate"
            . " ORDER BY date";
        $rows = $this->database->loadRows($sql);

        $rows = array_slice($rows, count($rows) - $this->period, $this->period);
        return $rows;
    }

    protected function _top_domains()
    {
        $config = Factory::getConfig();
        $limit = $config->top_domains;

        //Domains
        $sql = "SELECT d.name , COUNT(m.id) AS cnt "
            . " FROM messages AS m , domains AS d "
            . " WHERE m.from_domain = d.id "
            . " GROUP BY d.name "
            . " ORDER BY cnt DESC "
            . " LIMIT 0,10 ";
        $rows = $this->database->loadRows($sql);
        return Helpers::getTopItems($rows, $limit);
    }

    protected function _non_compliant_domains()
    {
        $sql = "SELECT d.name , COUNT(m.id) AS cnt"
            . " FROM messages AS m , domains AS d "
            . " WHERE m.from_domain = d.id "
            . " AND m.policy <> 15"
            . " GROUP BY d.name "
            . " ORDER BY cnt DESC "
            . " LIMIT 0,10 ";
        $rows = $this->database->loadRows($sql);
        return $rows;
    }

    protected function _arc()
    {

        $sql = "SELECT a.*, d.name , m.policy FROM arcseals AS a , messages AS m , domains AS d , selectors AS s"
            . " WHERE a.message = m.id "
            . " AND a.domain = d.id "
            . " AND a.selector = s.id";

        $rows = $this->database->loadRows($sql);
        return $rows;
    }
}
