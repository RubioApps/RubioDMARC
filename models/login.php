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

namespace RubioDMARC\Framework;

defined('_ODEXEC') or die;

use RubioDMARC\Framework\Factory;
use RubioDMARC\Framework\Language\Text;

class modelLogin extends Model
{
    public function display()
    {
        if (is_array($_POST) && isset($_POST['password'])) {
            $pwd    = $_POST['password'];

            if (Factory::checkToken() &&  md5($pwd) === $this->config->password) {
                //Log the user                
                $_SESSION['utoken'] = md5(session_id() . $this->config->password);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['error' => false, 'message' => Text::_('LOGIN_SUCCESS')]);
                exit(0);
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => true, 'message' => Text::_('LOGIN_ERROR')]);
            exit(0);
        }

        if (Factory::isLogged()) {
            $config = Factory::getConfig();
            header('Location:' . $config->live_site);
            exit(0);
        }
    }

    public function out()
    {
        $this->page->setFile('logout.php');
    }

    public function off()
    {
        unset($_SESSION['utoken']);
        setcookie('__Host_sid', '', time() - 86400);
        setcookie('__Host_prefs', '', time() - 86400);
        session_destroy();
        header('Location:' . Factory::Link());
        exit(0);
    }
}
