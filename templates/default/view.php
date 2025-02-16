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

use RubioDMARC\Framework\Language\Text;

?>
<?php if ($page->data): ?>
    <main class="container">
        <div class="fs-2 row border-bottom"><?= $page->data->jobid; ?></div>            
        <div class="fs-6 row d-flex">
            <div class="col p-0 text-start"><?= Text::_('DATE'); ?>: <?= $page->data->date; ?></div>
            <div class="col p-0 text-end"><?= Text::_('IP'); ?>: <?= $page->data->addr; ?></div>
        </div>
        <div class="row mt-3 mb-5 p-0">
            <div class="col-md p-3">
                <ul class="list-group">
                    <li class="list-group-item active" aria-current="true">
                        <div class="h5 fw-bolder"><?= Text::_('SPF'); ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DOMAIN'); ?>:</div>
                        <div class="me-0">
                            <a href="<?= $factory->Link('mta', 'id=' . $page->data->policy_domain); ?>">
                                <?= $page->data->policy_domain; ?>
                            </a>
                        </div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('AUTH_RESULTS'); ?>:</div>
                        <div class="me-0"><?= $page->data->spf_txt; ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DMARC_ALIGNMENT'); ?>:</div>
                        <div class="me-0"><?= $page->data->align_spf_txt; ?></div>
                    </li>
                </ul>
            </div>
            <div class="col-md p-3">
                <ul class="list-group">
                    <li class="list-group-item active" aria-current="true">
                        <div class="h5 fw-bolder"><?= Text::_('DKIM'); ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DOMAIN'); ?>:</div>
                        <div class="me-0">
                            <a href="<?= $factory->Link('mta', 'id=' . $page->data->signed_domain); ?>">
                                <?= $page->data->signed_domain; ?>
                            </a>
                        </div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('SELECTOR'); ?>:</div>
                        <div class="me-0"><?= $page->data->selector_name; ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('AUTH_RESULTS'); ?>:</div>
                        <div class="me-0"><?= $page->data->dkim_txt; ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DMARC_ALIGNMENT'); ?>:</div>
                        <div class="me-0"><?= $page->data->align_dkim_txt; ?></div>
                    </li>
                </ul>
            </div>
            <div class="col-md p-3">
                <ul class="list-group">
                    <li class="list-group-item active" aria-current="true">
                        <div class="h5 fw-bolder"><?= Text::_('DMARC'); ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DOMAIN'); ?>:</div>
                        <div class="me-0">
                            <a href="<?= $factory->Link('mta', 'id=' . $page->data->policy_domain); ?>">
                                <?= $page->data->policy_domain; ?>
                            </a>
                        </div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DMARC_SPF'); ?>:</div>
                        <div class="me-0"><?= $page->data->spf_txt; ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DMARC_DKIM'); ?>:</div>
                        <div class="me-0"><?= $page->data->dkim_txt; ?></div>
                    </li>
                    <li class="list-group-item d-flex">
                        <div class="me-auto fw-bold"><?= Text::_('DMARC_RESULTS'); ?>:</div>
                        <div class="me-0"><?= $page->data->policy_txt; ?></div>
                    </li>
                </ul>
            </div>
        </div>
        <h3><?= Text::_('DKIM'); ?></h3>
        <div class="row border rounded p-3 mb-3 text-wrap">
            <code><?= $page->data->dns_dkim; ?></code>
        </div>
        <h3><?= Text::_('SPF'); ?></h3>
        <div class="row border rounded p-3 mb-3 text-wrap">
            <code><?= $page->data->dns_spf; ?></code>
        </div>
    </main>
<?php endif; ?>