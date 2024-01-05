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

// List of DMRC Requests
$requests  = OpenDMARC\Framework\Model::getRequests($offset, $limit);
$pagination = OpenDMARC\Framework\Model::getPagination ( $offset, $limit);
$pagination->setAdditionalUrlParam('task', 'requests');
$pagination->setAdditionalUrlParam('limit', $limit);
$pagination->setAdditionalUrlParam('hl', $language->detectLanguage());

?>      
<script type="text/javascript">   
$(document).bind( "pageinit", function(e){ 
    $('.row-dns').on('mouseover',function(e){
        $td = $(this);
        if(!$td.attr('title')){
            $.getJSON( '<?= $config->live_site;?>/index.php?task=dns_dmarc&format=json&uri=' + $(this).html(), function(data){      
                $.each(data, function(k, v) {
                    if( k === 'txt'){
                        $td.attr('title' , v);
                    }
                });        
            });
        }
        });        
    });                
</script>
<div class="flex-nowrap h2 m1 p0"><?= Text::_('REQUESTS'); ?></div>
<div class="flex-nowrap m1 p0">
    <table class="pm-simple-table pm-simple-table--alternate-bg-row"
        <thead>
            <tr class="pm-simple-table-row-th">
                <th data-priority="persistent"><?= Text::_('FROM_DOMAIN'); ?></th>
                <th data-priority="1"><?= Text::_('FIRST_SEEN'); ?></th>
                <th data-priority="2"><?= Text::_('LAST_SENT'); ?></th>
                <th data-priority="persistent"><?= Text::_('ADKIM'); ?></th>
                <th data-priority="persistent"><?= Text::_('ASPF'); ?></th>
                <th data-priority="persistent"><?= Text::_('POLICY'); ?></th>
                <th data-priority="persistent"><?= Text::_('SPOLICY'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $row): ?>            
            <tr>                  
                <th class="row-dns"><?= $row->requester; ?></th>
                <td><?= $row->firstseen; ?></td>
                <td><?= $row->lastsent; ?></td>
                <td><?= $row->adkim_txt; ?></td>
                <td><?= $row->aspf_txt; ?></td>
                <td><?= $row->policy_txt; ?></td>
                <td><?= $row->spolicy_txt; ?></td>
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

 