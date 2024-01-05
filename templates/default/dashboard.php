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

// Charts data
$aggregate = OpenDMARC\Framework\Model::getAggregate();
$pivot = OpenDMARC\Framework\Model::getTimeline();
$domains = OpenDMARC\Framework\Model::getTopDomains($config->top_domains);
$rejects =  OpenDMARC\Framework\Model::getNonCompliantDomains();

?>
<script type="text/javascript">   
$(document).ready(function(event){
   
policydata = {
    type: 'doughnut',
    data: {
        labels: [<?php foreach($aggregate as $row): ?>'<?= Text::_($row->policy_txt);?>',<?php endforeach; ?>],                            
        datasets: [{
            label: '<?= Text::_('MESSAGES');?>',                        
            data: [<?php foreach($aggregate as $row): ?>'<?= $row->cnt;?>',<?php endforeach; ?>], 
            backgroundColor: [<?php foreach($aggregate as $row): ?>'<?= $row->color;?>',<?php endforeach; ?>],
            hoverOffset: 15
        }]
     },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: '<?= Text::_('POLICY');?>',
                font: {'size': 18 }
            },
            legend: {
                display: true,
                position: 'left'
            }
        }
    }                    
};  

domainsdata = {
    type: 'doughnut',
    data: {
        labels: [<?php foreach($domains as $row): ?>'<?= Text::_($row->name);?>',<?php endforeach; ?>],                            
        datasets: [{
            label: '<?= Text::_('MESSAGES');?>',                        
            data: [<?php foreach($domains as $row): ?>'<?= $row->cnt;?>',<?php endforeach; ?>], 
            hoverOffset: 15
            }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Top <?= $config->top_domains .  " " . Text::_('DOMAINS');?>',
                font: {'size': 18 }
            },
            legend: {
                display: true,
                position: 'left'
            }
        }
    }                    
};

rejectsdata = {
    type: 'doughnut',
    data: {
        labels: [<?php foreach($rejects as $row): ?>'<?= Text::_($row->name);?>',<?php endforeach; ?>],                            
        datasets: [{
            label: '<?= Text::_('REJECTIONS'); ?>',
            data: [<?php foreach($rejects as $row): ?>[<?= $row->cnt;?>],<?php endforeach; ?>],
            hoverOffset: 15
        }]
        },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: '<?= Text::_('NON_COMPLIANT') ;?>',
                font: {'size': 18 }
            },
            legend: {
                display: true,
                position: 'left'
            }
        }
    }                    
};                 

timelinedata = {
    type: 'bar',
    data: {
        labels: [<?php foreach($pivot as $row): ?>'<?= $row->mydate; ?>',<?php endforeach; ?>],
        datasets: [{ 
            label: '<?= Text::_('POLICY_PASS');?>',
            backgroundColor: '#4da358',
            data: [<?php foreach($pivot as $row): ?><?= $row->pass; ?>,<?php endforeach; ?>]
        },   
        {
            label: '<?= Text::_('POLICY_REJECT');?>',
            backgroundColor: 'lightred',
            data: [<?php foreach($pivot as $row): ?><?= $row->reject; ?>,<?php endforeach; ?>]
        },                         
        {
            label: '<?= Text::_('POLICY_QUARANTINE');?>',
            backgroundColor: 'yellow',
            data: [<?php foreach($pivot as $row): ?><?= $row->quarantine; ?>,<?php endforeach; ?>]
        }, 
        {
            label: '<?= Text::_('POLICY_UNKNOWN');?>',
            backgroundColor: 'lightblue',
            data: [<?php foreach($pivot as $row): ?><?= $row->unknown; ?>,<?php endforeach; ?>]
        }, 
        {
            label: '<?= Text::_('POLICY_NONE');?>',
            backgroundColor: 'gray',
            data: [<?php foreach($pivot as $row): ?><?= $row->none; ?>,<?php endforeach; ?>]
        }]
    },
    options: {
        responsive: true,
        elements: {
            bar: {
                borderWidth: 2
            },
        },
        scales: {
            x: {
                stacked: true,
                display: true,
                title: {
                    display: true,                  
                    text: '<?= Text::_('DATE');?>',
                    color: 'gray'
                },
                ticks: { color: 'gray' },
                grid: { display: false }                
            },                            
            y: {
                stacked: true,
                display: true,
                title: {
                    display: true,
                    text: '<?= Text::_('MESSAGES');?>',
                    color: 'gray'
                },
                ticks: { color: 'gray' },
                grid: { color: 'gray' }
            },                                               
        },
        plugins: {
            title: {
                display: true,
                text: '<?= Text::_('DAILY_RESULTS');?>',
                font: {'size': 18 }
            },
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    }                    
};  

new Chart($('#policy-chart').get(),policydata);               
new Chart($('#domains-chart').get(),domainsdata);                
new Chart($('#rejects-chart').get(),rejectsdata); 
new Chart($('#timeline').get(),timelinedata);                                 
   
});     
</script>       
<div class="flex-nowrap h2 m1 p0"><?= Text::_('HOME'); ?></div>
<div id="charts-panel" class="flex flex-autogrid">    
    <div class="flex w33"><canvas id="policy-chart"></canvas></div>
    <div class="flex w33"><canvas id="domains-chart"></canvas></div>                        
    <div class="flex w33"><canvas id="rejects-chart"></canvas></div>
    <div class="flex w100"><canvas id="timeline"></canvas></div>
</div>
