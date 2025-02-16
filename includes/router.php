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

class Router
{
    protected $params;
    protected $model;
    protected $format;

    public function __construct(&$params)
    {
        $this->params = &$params;
    }

    public function __destruct() {}

    public function dispatch()
    {
        $task   =   Factory::getTask();
        $func   =   Factory::getAction();
        $format =   Request::getVar('format', '', 'GET');

        //Clean task & function name
        $task = preg_replace('/[^a-zA-Z]+/', '', $task);
        $func = preg_replace('/[^a-zA-Z]+/', '', $func);

        //For direct access without login, check the validity of the token                
        if (!Factory::autoLogged() && !Factory::isLogged()) {
            switch ($task) {
                case 'login':
                    /* DO NOTHING */
                    break;
                default:
                    ob_end_clean();
                    echo '<script type="text/javascript">top.document.location.href = "' . Factory::Link('login') . '";</script>';
                    exit(0);
            }
        }

        if (file_exists(OD_MODELS . DIRECTORY_SEPARATOR . strtolower($task) . '.php')) {
            require_once(OD_MODELS . DIRECTORY_SEPARATOR . strtolower($task) . '.php');

            $classname  = '\RubioDMARC\Framework\model' . ucfirst($task);

            if (class_exists($classname) && method_exists($classname, $func)) {
                $this->model = new $classname($this->params);

                if ($this->model->$func() === false)
                    $this->_notfound();
            } else {
                error_log('Router::dispatch() : ' . $classname . '::' . $func . ' does not exist');
                $this->_notfound();
            }
        } else {
            error_log('Router::dispatch() : ' . $task . ' does not exist');
            error_log($_SERVER['REQUEST_URI']);
            $this->_notfound();
        }

        if ($format === 'raw' && file_exists(Factory::getTheme() . DIRECTORY_SEPARATOR . $task . '.raw.php'))
            return Factory::getTheme() . DIRECTORY_SEPARATOR . $task . '.raw.php';
        else
            return Factory::getTheme() . DIRECTORY_SEPARATOR . 'index.php';
    }

    protected function _notfound()
    {
        $page   =   Factory::getPage();
        $this->model = new \RubioDMARC\Framework\Model($this->params);
        $this->model->display();
        $page->setFile(null);
    }
}
