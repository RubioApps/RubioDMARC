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
defined('_ODEXEC') or die;
use OpenDMARC\Framework\Language\Text; 

if(isset($_GET['id']) && is_numeric($_GET['id']))
{
    $id = (int) $_GET['id'];
    $row  = OpenDMARC\Framework\Model::getMessageID($id);        
} else {
    die();
}

?>
<div class="flex-nowrap h2 m1 p0"><?= Text::_('DMARC_RESULTS') . " " . $row->jobid; ?></div>
<div class="flex-nowrap m1 p0">
    <p><?= Text::_('DATE'); ?>: <?= $row->date; ?></p>
    <p><?= Text::_('IP'); ?>: <?= $row->addr; ?></p>    
</div>
<div class="flex flex-nowrap m1 p0"> 
    <div class="lex flex-item-fluid plan-card h6">        
        <table class="pm-plans-table">
            <thead>
                <tr>
                    <td colspan="2"><?= Text::_('SPF'); ?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th><?= Text::_('DOMAIN'); ?></th>
                    <td><?= $row->policy_domain; ?></td>    
                </tr>
                <tr>
                    <th><?= Text::_('AUTH_RESULTS'); ?></th>
                    <td><?= $row->spf_txt; ?></td>
                </tr>
                <tr>                    
                    <th><?= Text::_('DMARC_ALIGNMENT'); ?></th>
                    <td><?= $row->align_spf_txt; ?></td>
                </tr>
            </tbody>                
        </table>                   
    </div> 
    <div class="flex flex-item-fluid plan-card h6">        
        <table class="pm-plans-table">
            <thead>
                <tr>
                    <td colspan="2"><?= Text::_('DKIM'); ?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th><?= Text::_('DOMAIN'); ?></th>
                    <td><?= $row->signed_domain; ?></td>    
                </tr>
                <tr>
                    <th><?= Text::_('SELECTOR'); ?></th>
                    <td><?= $row->selector_name; ?></td>
                </tr>
                <tr>                    
                    <th><?= Text::_('AUTH_RESULTS'); ?></th>
                    <td><?= $row->dkim_txt; ?></td>
                </tr>
                <tr>                    
                    <th><?= Text::_('DMARC_ALIGNMENT'); ?></th>
                    <td><?= $row->align_dkim_txt; ?></td>
                </tr>                
            </tbody>                
        </table>                   
    </div>     
    <div class="flex flex-item-fluid plan-card h6">        
        <table class="pm-plans-table">
            <thead>
                <tr>
                    <td colspan="2"><?= Text::_('DMARC'); ?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th><?= Text::_('DOMAIN'); ?></th>
                    <td><?= $row->policy_domain; ?></td>    
                </tr>
                <tr>
                    <th><?= Text::_('DMARC_POLICY'); ?></th>
                    <td><?= $row->arc_policy_txt; ?></td>
                </tr>
                <tr>                    
                    <th><?= Text::_('DMARC_SPF'); ?></th>
                    <td><?= $row->spf_txt; ?></td>
                </tr>
                <tr>                    
                    <th><?= Text::_('DMARC_DKIM'); ?></th>
                    <td><?= $row->dkim_txt; ?></td>
                </tr>  
                <tr>                    
                    <th><?= Text::_('DMARC_RESULTS'); ?></th>
                    <td><?= $row->policy_txt; ?></td>
                </tr>                 
            </tbody>                
        </table>                   
    </div>                 
</div>    