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

use DateTime;

defined('_ODEXEC') or die;

use RubioDMARC\Framework\Helpers;
use RubioDMARC\Framework\Request;
use RubioDMARC\Framework\Language\Text;

class modelMTA extends Model
{
    protected $host;
    protected $period;

    public function display()
    {
        $this->host     = Request::getString('id', '', 'GET');
        $this->period   = Request::getInt('period', '90', 'GET');
        $this->page->title      = Text::_('MTA');
        $this->page->data       = $this->_data();

        parent::display();
    }

    protected function _data()
    {

        $this->data['mta'] = $this->host;
        $this->data['dkim'] = '';
        $this->data['spf'] = '';

        //DKIM Record
        $sql = "SELECT s.name FROM selectors AS s , domains As d " . 
        " WHERE s.domain = d.id AND d.name = '" . $this->database->escape($this->host) . "'";
        if ($selector = $this->database->loadResult($sql)) {
            if ($dns = dns_get_record($selector . "._domainkey." . $this->host, DNS_TXT))
                $this->data['dkim'] = '';
                foreach ($dns as $line) {
                    $this->data['dkim'] .= htmlentities($line['txt']);                    
                }            
        }

        //SPF record          
        if ($dns = dns_get_record($this->host, DNS_TXT)) {
            foreach ($dns as $line) {
                $txt = $line['txt'];
                $parts = explode(" ", $txt);
                if (is_array($parts)) {
                    $parts = array_map('trim', $parts);
                    $parts = array_map('strtolower', $parts);
                    foreach ($parts as $part) {
                        if (!strcmp($part, "v=spf1"))
                            $this->data['spf'] = $txt;
                    }
                }
            }
        }

        // Messages
        $this->data['messages'] = $this->_messages();

        // Results
        $this->data['aggregate'] = $this->_aggregate();

        // Timeline
        $this->data['timeline']  = $this->_timeline();

        return $this->data;
    }

    protected function _messages()
    {
        $sql = "SELECT m.* , d.name AS from_domain , e.name AS env_domain , f.name AS eval_domain " .
            " FROM messages as m , domains AS d , domains AS e , domains AS f " .
            " WHERE m.from_domain = d.id " .
            " AND m.env_domain = e.id " .
            " AND m.policy_domain = f.id " .
            " AND d.name = '" . $this->database->escape($this->host) . "'" .
            " AND m.date BETWEEN (NOW() - INTERVAL " . (int) $this->period . " DAY) AND NOW() "
            ;

        $rows = $this->database->loadRows($sql);
        foreach ($rows as $row) {
            Helpers::getDisposition($row);
            Helpers::getPolicy($row);
            Helpers::getAlignmentSet($row);
            Helpers::getARCEvaluation($row);
        }
        return $rows;
    }

    protected function _aggregate()
    {
        $sql = "SELECT policy , COUNT(m.jobid) AS cnt FROM messages AS m , domains AS d " .
            " WHERE m.policy_domain = d.id " . 
            " AND d.name LIKE '" . $this->database->escape($this->host) . "' " .
            " AND m.date BETWEEN (NOW() - INTERVAL " . (int) $this->period . " DAY) AND NOW() " .
            " GROUP BY m.policy";
        $rows = $this->database->loadRows($sql);
        foreach ($rows as $row) {
            $row = Helpers::getPolicy($row);
        }
        return $rows;
    }

    protected function _timeline()
    {        
        $sql = "SELECT DATE_FORMAT(m.date,'%Y-%m-%d') AS dt, "
            . " SUM(CASE WHEN m.policy = " . POLICY_PASS . "  THEN 1 ELSE 0 END) AS pass, "
            . " SUM(CASE WHEN m.policy =  " . POLICY_QUARANTINE . " THEN 1 ELSE 0 END) AS quarantine, "
            . " SUM(CASE WHEN m.policy IN (" . POLICY_REJECT . "," . POLICY_UNKNOWN . "," . POLICY_NONE . ")  THEN 1 ELSE 0 END) AS reject "
            . " FROM messages AS m , domains as d "
            . " WHERE m.policy_domain = d.id "
            . " AND d.name LIKE '" . $this->database->escape($this->host) . "' "
            . " AND m.date BETWEEN (NOW() - INTERVAL " . $this->period . " DAY) AND NOW() "
            . " GROUP BY dt"
            . " ORDER BY m.date DESC";

        $rows = $this->database->loadRows($sql);

        $records = [];
        foreach ($rows as $row)
            $records[$row->dt] = $row;

        $now = new DateTime();
        $month = new \DateInterval('P' . $this->period . 'D');
        $month->invert = true;
        $now->add($month);        
        $day = new \DateInterval('P1D');

        $result = [];
        for ($i = 1; $i <= $this->period; $i++) {        
            $item = new \stdClass();
            $item->dt = $now->format('Y-m-d');
            $item->pass = $item->quarantine = $item->reject = 0;
            if (isset($records[$item->dt])) $item = $records[$item->dt];            
            $result[] = $item;            
            $now->add($day);    
        }
        return $result;
    }
}
