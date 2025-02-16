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
<?php if ($page->data): ?>
    <main class="container">
        <div class="row">
            <div class="col-sm-8 col-9 h3"><?= $page->data['mta']; ?></div>
            <div class="col-sm-4 col-3 form-floating">
                <form class="form" action="<?= $factory->Link('mta', 'id=' . Request::getVar('id', null, 'GET')); ?>" method="GET">
                    <select name="period" class="form-select" id="pivot-period" aria-label="Pivot period">
                        <?php
                        $days = [30, 60, 90];
                        foreach ($days as $d) {
                            $selected = (Request::getInt('period', 90, 'GET') == $d) ? ' selected' : '';
                        ?>
                            <option value="<?= $d ?>" <?= $selected; ?>><?= $d . ' ' . Text::_('DAYS'); ?></option>
                        <?php } ?>
                    </select>
                </form>
            </div>
        </div>
        <section>
            <canvas id="timeline"></canvas>
        </section>
        <section>
            <div class="h5"><?= Text::_('SUMMARY'); ?></div>
            <div class="row border rounded p-3 mb-3 text-wrap">
                <?php if ($page->data['aggregate']): ?>
                    <?php foreach ($page->data['aggregate'] as $row): ?>
                        <div class="row h2" style="color:<?= $row->color; ?>">
                            <div class="col-8 ms-0"><?= Text::_($row->policy_txt); ?></div>
                            <div class="col-4 text-end me-0"><?= Text::_($row->cnt); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    No data
                <?php endif; ?>
            </div>
        </section>        
        <section>
            <div class="h5"><?= Text::_('DKIM'); ?></div>
            <div class="row border rounded p-3 mb-3 text-wrap">
                <code><?= $page->data['dkim']; ?></code>
            </div>
        </section>
        <section>
            <div class="h5"><?= Text::_('SPF'); ?></div>
            <div class="row border rounded p-3 mb-3 text-wrap">
                <code><?= $page->data['spf']; ?></code>
            </div>
        </section>
        <section>
            <div class="h5"><?= Text::_('MESSAGES'); ?>&nbsp;(<?= count($page->data['messages']); ?>)</div>
            <div id="messages"></div>
        </section>
    </main>
<?php endif; ?>
<?= $page->addCDN('js', $factory->getAssets() . '/chart.js'); ?>

<script type="text/javascript">
    $(document).ready(function(event) {

        $('#pivot-period').on('change', function() {
            $('#pivot-period').parent().trigger('submit');
        });

        $.get('<?= $factory->Link('messages', 'domain=' . Request::getString('id', null, 'GET') , 'period=' . Request::getString('period', '90', 'GET'), 'format=raw'); ?>', 
            function(data) {
                $('#messages').html(data);
            })

        timelinedata = {
            type: 'bar',
            data: {
                labels: [<?php foreach ($page->data['timeline'] as $row): ?> '<?= $row->dt; ?>', <?php endforeach; ?>],
                datasets: [{
                        label: '<?= Text::_('POLICY_PASS'); ?>',
                        backgroundColor: 'green',
                        data: [<?php foreach ($page->data['timeline'] as $row): ?><?= $row->pass; ?>, <?php endforeach; ?>]
                    },
                    {
                        label: '<?= Text::_('POLICY_QUARANTINE'); ?>',
                        backgroundColor: 'orange',
                        data: [<?php foreach ($page->data['timeline'] as $row): ?><?= $row->quarantine; ?>, <?php endforeach; ?>]
                    },
                    {
                        label: '<?= Text::_('POLICY_REJECT'); ?>',
                        backgroundColor: 'red',
                        data: [<?php foreach ($page->data['timeline'] as $row): ?><?= $row->reject; ?>, <?php endforeach; ?>]
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
                        suggestedMin: 10,
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

        new Chart($('#timeline').get(), timelinedata);

    });
</script>