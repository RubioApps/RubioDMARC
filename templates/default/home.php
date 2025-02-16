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

defined('_ODEXEC') or die;

use RubioDMARC\Framework\Request;
use RubioDMARC\Framework\Language\Text;

?>
<main class="container">
    <div class="h1"><?= Text::_('HOME'); ?></div>
    <div id="charts-panel" class="p-3">
        <div class="row d-flex">
            <div class="col m-1 border rounded align-middle p-2">
                <div class="m-0 pt-3 pb-3">
                    <?php foreach ($page->data['aggregate'] as $row): ?>
                        <div class="row fs-3" style="color:<?= $row->color; ?>">
                            <div class="col-8 ms-0"><?= Text::_($row->policy_txt); ?></div>
                            <div class="col-4 text-end me-0"><?= Text::_($row->cnt); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col m-1 border rounded p-2"><canvas id="domains-chart"></canvas></div>
            <div class="col m-1 border rounded p-2"><canvas id="rejects-chart"></canvas></div>
        </div>
        <div class="row">
            <div class="w-100"><canvas id="timeline"></canvas></div>
        </div>
    </div>
</main>
<section class="container">
    <div class="form-floating">
        <form class="form" action="<?= $factory->Link(); ?>" method="GET">
            <label for="pivot-period"><?= Text::_('TIMESLOT'); ?></label>
            <select name="period" class="form-select" id="pivot-period" aria-label="Pivot period">
                <?php
                $days = [30, 60, 90, 180, 365];
                foreach ($days as $d) {
                    $selected = (Request::getInt('period', 30, 'GET') == $d) ? ' selected' : '';
                ?>
                    <option value="<?= $d ?>" <?= $selected; ?>><?= $d . ' ' . Text::_('DAYS'); ?></option>
                <?php } ?>
            </select>
        </form>
    </div>
</section>

<?= $page->addCDN('js', $factory->getAssets() . '/chart.js'); ?>

<script type="text/javascript">
    $(document).ready(function(event) {

        $('#pivot-period').on('change', function() {
            $('#pivot-period').parent().trigger('submit');
        });

        const domainsdata = {
            type: 'doughnut',
            data: {
                labels: [<?php foreach ($page->data['domains'] as $row): ?> '<?= Text::_($row->name); ?>', <?php endforeach; ?>],
                datasets: [{
                    label: '<?= Text::_('MESSAGES'); ?>',
                    data: [<?php foreach ($page->data['domains'] as $row): ?> '<?= $row->cnt; ?>', <?php endforeach; ?>],
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Top <?= $config->top_domains .  " " . Text::_('DOMAINS'); ?>',
                        font: {
                            'size': 22
                        },
                        color: 'darkgray',
                    },
                    legend: {
                        display: true,
                        position: 'left',
                        labels: {
                            color: 'darkgray'
                        }
                    }
                }
            }
        };

        const rejectsdata = {
            type: 'doughnut',
            data: {
                labels: [<?php foreach ($page->data['rejects'] as $row): ?> '<?= Text::_($row->name); ?>', <?php endforeach; ?>],
                datasets: [{
                    label: '<?= Text::_('REJECTIONS'); ?>',
                    data: [<?php foreach ($page->data['rejects'] as $row): ?>[<?= $row->cnt; ?>], <?php endforeach; ?>],
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: '<?= Text::_('NON_COMPLIANT'); ?>',
                        font: {
                            'size': 22
                        },
                        color: 'darkgray',
                    },
                    legend: {
                        display: true,
                        position: 'left',
                        labels: {
                            color: 'darkgray'
                        }
                    }
                },
                interaction: {
                    mode: 'dataset'
                }                
            }
        };

        const timelinedata = {
            type: 'bar',
            data: {
                labels: [<?php foreach ($page->data['pivot'] as $row): ?> '<?= $row->mydate; ?>', <?php endforeach; ?>],
                datasets: [{
                        label: '<?= Text::_('POLICY_PASS'); ?>',
                        backgroundColor: 'green',
                        data: [<?php foreach ($page->data['pivot'] as $row): ?><?= $row->pass; ?>, <?php endforeach; ?>]
                    },
                    {
                        label: '<?= Text::_('POLICY_QUARANTINE'); ?>',
                        backgroundColor: 'orange',
                        data: [<?php foreach ($page->data['pivot'] as $row): ?><?= $row->quarantine; ?>, <?php endforeach; ?>]
                    },
                    {
                        label: '<?= Text::_('POLICY_REJECT'); ?>',
                        backgroundColor: 'red',
                        data: [<?php foreach ($page->data['pivot'] as $row): ?><?= $row->reject; ?>, <?php endforeach; ?>]
                    }
                ]
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
                            text: '<?= Text::_('DATE'); ?>',
                            color: 'darkgray'
                        },
                        ticks: {
                            color: 'darkgray'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        display: true,
                        title: {
                            display: true,
                            text: '<?= Text::_('MESSAGES'); ?>',
                            color: 'darkgray'
                        },
                        ticks: {
                            stepSize: 1,
                            color: 'darkgray'
                        },
                        grid: {
                            color: 'darkgray'
                        }
                    },
                },
                plugins: {
                    title: {
                        display: true,
                        text: '<?= Text::_('DAILY_RESULTS'); ?>',
                        font: {
                            'size': 22
                        },
                        color: 'darkgray',
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: 'darkgray'
                        }
                    }
                }
            }
        };

        new Chart($('#domains-chart').get(), domainsdata);
        new Chart($('#rejects-chart').get(), rejectsdata);
        new Chart($('#timeline').get(), timelinedata);

    });
</script>