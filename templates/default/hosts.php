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
    <form action="<?= $factory->Link('hosts'); ?>" method="get">
        <div class="input-group mb-3">
            <input name="domain" type="text" class="form-control" placeholder="<?= Text::_('DOMAIN'); ?>" value="<?= Request::getString('domain', '', 'GET'); ?>" />
            <button type="submit" class="btn btn-primary"><?= Text::_('SUBMIT'); ?></button>
            <button type="reset" class="btn btn-secondary"><?= Text::_('RESET'); ?></button>
        </div>
    </form>
</section>
<main class="container">
    <div class="container border-bottom mt-2">
        <div class="h1"><?= Text::_('HOSTS'); ?></div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col"><?= Text::_('FROM_DOMAIN'); ?></th>
                    <th scope="col"><?= Text::_('FIRST_SEEN'); ?></th>
                    <th scope="col"><?= Text::_('ADKIM'); ?></th>
                    <th scope="col"><?= Text::_('ASPF'); ?></th>
                    <th scope="col"><?= Text::_('POLICY'); ?></th>
                    <th scope="col"><?= Text::_('SPOLICY'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($page->data as $row): ?>
                    <tr>
                        <th scope="row">
                            <a href="<?= $factory->Link('mta', 'id=' . $row->requester); ?>">
                                <?= $row->requester; ?>
                            </a>
                        </th>
                        <td><?= $row->firstseen; ?></td>
                        <td><?= $row->adkim_txt; ?></td>
                        <td><?= $row->aspf_txt; ?></td>
                        <td><?= $row->policy_txt; ?></td>
                        <td><?= $row->spolicy_txt; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<section class="container-lg">
    <?= $page->pagination->getPagesLinks(); ?>
</section>