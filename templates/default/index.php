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
<!DOCTYPE html>
<html lang="<?= $language->getTag(); ?>" <?= ($language->isRtl() ? ' dir="rtl"' : ''); ?> data-bs-theme="<?= $page->params['mode']; ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta name="robots" content="noindex,nofollow" />
    <meta name="keywords" content="brave, search" />
    <meta name="description" content="brave, search" />
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $config->live_site; ?>/favicons/favicon-16x16.png" />
    <title><?= $page->title . ' - ' . $config->sitename; ?></title>
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $factory->getAssets(); ?>/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="48x48" href="<?= $factory->getAssets(); ?>/favicons/favicon-48x48.png" />
    <link rel="icon" type="image/png" sizes="64x64" href="<?= $factory->getAssets(); ?>/favicons/favicon-64x64.png" />
    <link rel="manifest" href="/tv/manifest.json">
    <!-- Basic Jquery -->
    <?= $page->addCDN('js', 'https://code.jquery.com/jquery-3.7.1.min.js', 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=', 'anonymous'); ?>
    <?= $page->addCDN('js', 'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js', 'sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=', 'anonymous'); ?>
    <!-- Bootstrap v5 -->
    <?= $page->addCDN('css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', 'sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN', 'anonymous'); ?>
    <?= $page->addCDN('js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', 'sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL', 'anonymous'); ?>
    <?= $page->addCDN('css', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css'); ?>
    <!-- Additional JS -->
    <?= $page->addCDN('js', $factory->getAssets() . '/od.js'); ?>
    <!-- Additional styles -->
    <?= $page->addCDN('css', $factory->getAssets() . '/default.css'); ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $.od.init('<?= $config->live_site; ?>', '<?= $factory->getAssets(); ?>');
        });
    </script>
</head>

<body>
    <?php if ($factory->isLogged()): ?>
        <?php require_once $page->getFile('header'); ?>
    <?php endif; ?>
    <div class="od-main mt-3 my-3 mb-5 pb-3">
        <?php require_once $page->getFile(); ?>
    </div>
    <?php require_once $page->getFile('footer'); ?>
    <?php require_once $page->getFile('toast'); ?>
    <?php echo $page->getJScripts(); ?>
</body>

</html>