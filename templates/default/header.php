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
<header class="navbar navbar-expand-md navbar-dark od-navbar sticky-top">
    <nav class="container-xl flex-wrap flex-md-nowrap">
        <div class="d-flex">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <a class="navbar-brand od-brand text-center text-truncate" href="<?= $factory->Link(); ?>">
            <div class="h3 fw-bold"><?= $config->sitename; ?></div>
        </a>
        <div class="collapse navbar-collapse" id="mainmenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($page->menu as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $item->link; ?>"><?= $item->label ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="d-flex mt-2 mb-2 mt-lg-1">
                <button id="btn-theme-switch"
                    class="btn bi <?= $page->params['mode'] != 'dark' ? 'btn-primary bi-moon-stars' : 'btn-warning bi-sun'; ?>"
                    data-mode="<?= $page->params['mode']; ?>">
                </button>
                <?php if ($factory->isLogged() && !$config->use_autolog): ?>
                    <a class="nav-link p-0 mt-0 ms-1" href="<?= $factory->Link('login.off'); ?>">
                        <div class="btn btn-secondary">
                            <span class="bi bi-power"></span>
                        </div>
                    </a>
                <?php endif; ?>
                <div class="ms-1">
                    <select class="form-select" name="hl" id="btn-lang-switch">
                        <?php foreach ($language->getKnownLanguages() as $lang): ?>
                            <option value="<?= $lang['tag']; ?>" <?= ($lang['tag'] !== $page->params['hl'] ? '' : 'selected'); ?>>
                                <?= Text::_($lang['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </nav>
</header>