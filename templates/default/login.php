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

use \RubioDMARC\Framework\Language\Text;
?>

<!-- Login -->
<main role="main" class="container container-md mx-auto my-auto">
    <form><?= $factory::getToken(); ?></form>
    <div class="row justify-content-center p-3 flex-nowrap mt-5">
        <div class="col-auto border rounded">
            <div class="row p-1">
                <div class="col text-center mt-2">
                    <label for="pwd" class="col-form-label fw-bolder"><?= Text::_('PASSWORD'); ?></label>
                </div>
            </div>
            <div class="row p-1">
                <div class="col">
                    <input type="password" id="pwd" name="password" class="form-control" value="" />
                </div>
            </div>
            <div class="row p-1">
                <div class="col text-center mb-2">
                    <button id="btn-submit" type="button" class="btn btn-primary"><?= Text::_('SUBMIT'); ?></button>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- JS -->
<!-- JS -->
<script type="text/javascript">
    jQuery(document).ready(function() {
        $.od.livesite = '<?= $config->live_site; ?>';
        $.od.logged = <?= $factory->isLogged() ? 'true' : 'false'; ?>;
        $.od.login('#btn-submit');
    });
</script>