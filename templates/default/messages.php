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
<section class="container-lg">
    <div class="d-flex">
        <div class="ms-auto position-relative">
            <a class="btn btn-primary bi-plus" id="btn-filter" data-bs-toggle="collapse" href="#filter-box" role="button" aria-expanded="false" aria-controls="filter-box">
                <?= Text::_('FILTER'); ?>
            </a>
        </div>
    </div>
    <div id="filter-box" class="collapse mb-3">
        <form action="<?= $factory->Link('messages'); ?>" method="get">
            <div class="row d-flex">
                <div class="col-auto">
                    <label for="domain" class="form-label"><?= Text::_('POLICY_DOMAIN'); ?></label>
                    <input name="domain" type="text" class="form-control" id="domain" value="<?= Request::getString('domain', '', 'GET'); ?>" />
                </div>
                <div class="col-auto">
                    <label for="dkim-aligned" class="form-label"><?= Text::_('DKIM_ALIGN'); ?></label>
                    <select name="dkim" id="dkim-alignment" class="form-select">
                        <option value="0">---</option>
                        <option value="4"><?= Text::_('ALIGNMENT_SET_YES'); ?></option>
                        <option value="5"><?= Text::_('ALIGNMENT_SET_NO'); ?></option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="spf-aligned" class="form-label"><?= Text::_('SPF_ALIGN'); ?></label>
                    <select name="spf" id="spf-alignment" class="form-select">
                        <option value="0">---</option>
                        <option value="4"><?= Text::_('ALIGNMENT_SET_YES'); ?></option>
                        <option value="5"><?= Text::_('ALIGNMENT_SET_NO'); ?></option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="policy" class="form-label"><?= Text::_('POLICY'); ?></label>
                    <select name="policy" id="policy" class="form-select">
                        <option value="0">---</option>
                        <option value="14"><?= Text::_('POLICY_UNKNOWN'); ?></option>
                        <option value="15"><?= Text::_('POLICY_PASS'); ?></option>
                        <option value="16"><?= Text::_('POLICY_REJECT'); ?></option>
                        <option value="17"><?= Text::_('POLICY_QUARANTINE'); ?></option>
                        <option value="18"><?= Text::_('POLICY_NONE'); ?></option>
                    </select>
                </div>
            </div>
            <div class="row pt-1 mt-3">
                <div class="col-6">
                    <button type="submit" class="btn btn-primary w-100"><?= Text::_('SUBMIT'); ?></button>
                </div>
                <div class="col-6">
                    <button type="reset" class="btn btn-secondary w-100"><?= Text::_('RESET'); ?></button>
                </div>
            </div>
        </form>
    </div>
</section>
<main class="container">
    <div class="container border-bottom mt-2">
        <div class="h1"><?= Text::_('MESSAGES'); ?></div>
    </div>
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
    <?= $page->pagination->getPagesLinks(); ?>
</section>

<script type="text/javascript">
    jQuery(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip()

        $('#dkim-alignment').val('<?= Request::getInt('dkim', 0, 'GET'); ?>');
        $('#spf-alignment').val('<?= Request::getInt('spf', 0, 'GET'); ?>');
        $('#policy').val('<?= Request::getInt('policy', 0, 'GET'); ?>');

        if ($('#domain').val() !== '' ||
            $('#dkim-alignment').val() !== '0' ||
            $('#spf-alignment').val() !== '0' ||
            $('#policy').val() !== '0'
        ) {
            const btn = new bootstrap.Collapse('#filter-box');
            btn.show();
        }

    });
</script>