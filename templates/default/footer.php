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


?>
<footer class="container-md p-1 mt-5 mx-auto w-100">
  <div class="tv-footer">
    <nav class="navbar navbar-expand">
      <ul class="navbar-nav d-flex">
        <?php foreach ($config->links as $k => $v): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $v; ?>" target="_blank"><?= htmlspecialchars($k); ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>
</footer>
<script type="text/javascript">
  jQuery(document).ready(function() {
    $('#hl-selector').change(function() {
      $('#hl-form').submit();
    });
  });
</script>