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
use RubioDMARC\Framework\Request;
use RubioDMARC\Framework\Language\Text;

class modelView extends Model
{

    public function display()
    {
        $this->page->title      = Text::_('MESSAGE');
        $this->page->data       = $this->_data();

        if ($this->page->data) {
            //Complete with text
            Helpers::getDisposition($this->page->data);
            Helpers::getPolicy($this->page->data);
            Helpers::getAlignmentSet($this->page->data);
            Helpers::getEvaluation($this->page->data);
            Helpers::getARCEvaluation($this->page->data);
        } else
            $this->page->setFile('404.php');

        parent::display();
    }

    protected function _data()
    {
        $id = Request::getInt('id', 0, 'GET');
        if ($id) {
            $sql = " SELECT m.date , m.jobid , m.policy , m.disp , m.ip , m.spf , m.align_dkim, m.align_spf , m.arc , m.arc_policy , "
                . " d.name as env_domain , e.name as from_domain , p.name as policy_domain , q.name AS signed_domain , "
                . " s.name as selector_name , s.firstseen, t.addr , x.pass as dkim"
                . " FROM messages as m , domains AS d , domains AS e ,  domains AS p  , domains AS q , selectors AS s , signatures AS x , ipaddr as t"
                . " WHERE m.env_domain = d.id "
                . " AND m.from_domain = e.id "
                . " AND m.policy_domain = p.id "
                . " AND ip = t.id "
                . " AND m.id = x.message "
                . " AND x.domain = q.id "
                . " AND x.selector = s.id "
                . " AND m.id = " . (int) $id;

            $item = $this->database->loadObject($sql);

            if (!$item)
                return false;

            //Get DKIM DNS record
            if (!empty($item->selector_name) && !empty($item->signed_domain)) {
                if ($dns = dns_get_record($item->selector_name . "._domainkey." . $item->signed_domain, DNS_TXT)) {
                    $item->dns_dkim = $dns[0]['txt'];
                } else {
                    $item->dns_dkim = '';
                }
            }

            //Get SPF DNS record
            if (!empty($item->env_domain)) {
                if ($dns = dns_get_record($item->env_domain, DNS_TXT)) {
                    foreach ($dns as $line) {
                        $txt = $line['txt'];
                        $parts = explode(" ", $txt);
                        if (is_array($parts)) {
                            $parts = array_map('trim', $parts);
                            $parts = array_map('strtolower', $parts);
                            foreach ($parts as $part) {
                                if (!strcmp($part, "v=spf1"))
                                    $item->dns_spf = $txt;
                            }
                        } else
                            $item->dns_spf = '';
                    }
                }
            }
            $this->data = $item;
        }
        return $this->data;
    }
}
