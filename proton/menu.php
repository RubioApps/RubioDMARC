<!DOCTYPE html>
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
 | This file forms part of the RubioTV software.                           |
 |                                                                         |
 | If you wish to use this file in another project or create a modified    |
 | version that will not be part of the RubioTV Software, you              |
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

use OpenDMARC\Framework\Language\Text; 
$url = 'task=' . $factory->getTask() . '&limit=' . $limit . '&offset=' . $offset;
?>
<nav class="flex flex-nowrap h6">
    <a class="navigation__link" href="<?= $factory->getConfig()->live_site; ?>?hl=<?= $factory->getLangTag();?>"><?= Text::_('HOME'); ?></a></li>
    <a class="navigation__link" href="<?= $factory->getConfig()->live_site; ?>?task=messages&hl=<?= $factory->getLangTag();?>"><?= Text::_('MESSAGES'); ?></a>
    <a class="navigation__link" href="<?= $factory->getConfig()->live_site; ?>?task=requests&hl=<?= $factory->getLangTag();?>"><?= Text::_('REQUESTS'); ?></a>
    <form name="hl-form" id="hl-form" action="/opendmarc" method="GET" target="_self" class="navigation__link">
        <select name="hl" id="hl-selector" class="h6 navigation__icon gear-white">
        <?php 
        foreach ($language->getKnownLanguages() as $lang) { 
            if(!strcmp($language->getTag() ,$lang['tag'])) {
                echo '<option class="is-active" value="' . $lang['tag'] . '" selected>' . $lang['nativeName'] . '</option>';
            } else {
                echo '<option value="' . $lang['tag'] . '">' . $lang['nativeName'] . '</option>';
            }
        } 
        ?>
        </select>
        <input type="hidden" name="task" value="<?= $factory->getTask(); ?>" />
        <input type="hidden" name="limit" value="<?= $limit; ?>" />
        <input type="hidden" name="offset" value="<?= $offset; ?>" />                    
    </form> 
</nav>
