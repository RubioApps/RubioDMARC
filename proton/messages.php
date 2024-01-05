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

// List of messages
$messages   = OpenDMARC\Framework\Model::getMessages($offset, $limit);
$pagination = OpenDMARC\Framework\Model::getPagination ( $offset, $limit);
$pagination->setAdditionalUrlParam('task', 'messages');
$pagination->setAdditionalUrlParam('limit', $limit);
$pagination->setAdditionalUrlParam('hl', $language->detectLanguage()); 
?>
<script type="text/javascript">   
$(document).ready( function(){
                               
});     
</script>       

<div class="flex-nowrap h2 m1 p0"><?= Text::_('MESSAGES'); ?></div>
<div class="flex-nowrap m1 p0">
    <table class="pm-simple-table pm-simple-table--alternate-bg-row">
        <thead>
            <tr class="pm-simple-table-row-th">
                <th><?= Text::_('MESSAGE'); ?></th>
                <th><?= Text::_('DATE'); ?></th>                          
                <th><?= Text::_('POLICY_DOMAIN'); ?></th>                
                <th><?= Text::_('DKIM_ALIGN'); ?></th>
                <th><?= Text::_('SPF_ALIGN'); ?></th>               
                <th><?= Text::_('POLICY'); ?></th>                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $row): ?>            
            <tr>                    
                <th><a href="<?= $factory->getConfig()->live_site; ?>?task=view&id=<?= $row->id; ?>&hl=<?= $factory->getLangTag();?>"><?= $row->jobid; ?></a></th>
                <td><?= $row->date; ?></td>             
                <td><?= $row->eval_domain; ?></td>                
                <td><?= $row->align_dkim_txt; ?></td>
                <td><?= $row->align_spf_txt; ?></td>             
                <td><?= $row->policy_txt; ?></td>                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="flex-nowrap m1 p0">
    <div class="flex-autogrid-item h6 aligncenter">
        <?= $pagination->getPagesLinks(); ?> 
    </div>
</div>

            
