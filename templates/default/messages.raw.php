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
<main>
    <?php if ($page->data): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th scope="col"><?= Text::_('MESSAGE'); ?></th>
                        <th scope="col"><?= Text::_('DATE'); ?></th>
                        <th scope="col"><?= Text::_('POLICY_DOMAIN'); ?></th>
                        <th scope="col"><?= Text::_('DKIM_ALIGN'); ?></th>
                        <th scope="col"><?= Text::_('SPF_ALIGN'); ?></th>
                        <th scope="col"><?= Text::_('RESULT'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($page->data as $row): ?>
                        <tr class="text-center">
                            <th scope="row">
                                <a href="<?= $factory->Link('view', 'id=' . $row->id . ':' . $row->jobid); ?>"><?= $row->jobid; ?></a>
                            </th>
                            <td class="text-nowrap"><?= $row->date; ?></td>
                            <td class="text-start">
                                <a href="<?= $factory->Link('mta', 'id=' . $row->from_domain); ?>"><?= $row->from_domain; ?></a>
                            </td>
                            <td><?= $row->align_dkim_txt; ?></td>
                            <td><?= $row->align_spf_txt; ?></td>
                            <td><?= $row->policy_txt; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
<section class="container">
    <?= $page->pagination->getPagesLinks(true); ?>
</section>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('[data-toggle="tooltip"]').tooltip()

        //Pagination on the sidebar has to be modified to target to the div
        $('#messages a.page-link').on('click', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $("#messages").html(data);
            });
        });
    });
</script>