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

class Database
{

    protected $options;
    protected $connection;
    protected $host;
    protected $port;
    protected $user;
    protected $password;
    protected $database;
    protected $sql;
    protected $nameQuote;

    public function __construct(array $options)
    {
        // Set class options.
        $this->options = $options;

        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }


    public function connect()
    {

        if ($this->connection) {
            return;
        }

        $port = isset($this->options['port']) ? $this->options['port'] : 3306;

        if (preg_match('/^unix:(?P<socket>[^:]+)$/', $this->options['host'], $matches)) {
            // UNIX socket URI, e.g. 'unix:/path/to/unix/socket.sock'
            $this->options['host']   = null;
            $this->options['socket'] = $matches['socket'];
            $this->options['port']   = null;
        } elseif (preg_match(
            '/^(?P<host>((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))(:(?P<port>.+))?$/',
            $this->options['host'],
            $matches
        )) {
            // It's an IPv4 address with or without port
            $this->options['host'] = $matches['host'];
            if (!empty($matches['port'])) {
                $port = $matches['port'];
            }
        } elseif (preg_match('/^(?P<host>\[.*\])(:(?P<port>.+))?$/', $this->options['host'], $matches)) {
            // We assume square-bracketed IPv6 address with or without port, e.g. [fe80:102::2%eth1]:3306
            $this->options['host'] = $matches['host'];

            if (!empty($matches['port'])) {
                $port = $matches['port'];
            }
        } elseif (preg_match('/^(?P<host>(\w+:\/{2,3})?[a-z0-9\.\-]+)(:(?P<port>[^:]+))?$/i', $this->options['host'], $matches)) {
            // Named host (e.g example.com or localhost) with or without port
            $this->options['host'] = $matches['host'];

            if (!empty($matches['port'])) {
                $port = $matches['port'];
            }
        } elseif (preg_match('/^:(?P<port>[^:]+)$/', $this->options['host'], $matches)) {
            // Empty host, just port, e.g. ':3306'
            $this->options['host'] = 'localhost';
            $port                  = $matches['port'];
        }

        // ... else we assume normal (naked) IPv6 address, so host and port stay as they are or default
        // Get the port number or socket name
        if (is_numeric($port)) {
            $this->options['port'] = (int) $port;
        } else {
            $this->options['socket'] = $port;
        }

        $this->host     = $this->options['host'] ?? 'localhost';
        $this->user     = $this->options['user'] ?? 'root';
        $this->password = $this->options['password'] ?? '';
        $this->database = $this->options['database'] ?? '';

        $this->connection = mysqli_init();
        if (!$this->connection) {
            die("mysqli_init failed");
        }

        if (!mysqli_real_connect(
            $this->connection,
            $this->options['host'],
            $this->options['user'],
            $this->options['password'],
            $this->options['database']
        )) {
            die("Connect Error: " . mysqli_connect_error());
        }
    }

    function query($sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        return mysqli_query($this->connection, $this->sql);
    }

    function escape($text, $extra = false)
    {
        $this->connect();

        $result = mysqli_real_escape_string($this->connection, $text);

        if ($extra) {
            $result = addcslashes($result, '%_');
        }

        return $result;
    }

    function loadRows($sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        $rows = [];
        if ($result = $this->query()) {
            while ($row = $result->fetch_object()) {
                $rows[] = $row;
            }
            $result->free_result();
        }
        return $rows;
    }

    function getNumRows($sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        $result = $this->query($sql);
        return mysqli_num_rows($result);
    }

    function loadResult($sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        if (!($cur = $this->query())) {
            return null;
        }
        $ret = null;
        if ($row = mysqli_fetch_row($cur)) {
            $ret = $row[0];
        }
        mysqli_free_result($cur);
        return $ret;
    }

    function loadResultArray($numinarray = 0)
    {
        if (!($cur = $this->connection->query())) {
            return null;
        }
        $array = [];
        while ($row = mysqli_fetch_row($cur)) {
            $array[] = $row[$numinarray];
        }
        mysqli_free_result($cur);
        return $array;
    }

    function loadAssoc($sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        if (!($cur = $this->query())) {
            return null;
        }
        $ret = null;
        if ($array = mysqli_fetch_assoc($cur)) {
            $ret = $array;
        }
        mysqli_free_result($cur);
        return $ret;
    }

    function loadAssocList($key = '', $sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        if (!($cur = $this->query())) {
            return null;
        }
        $array = [];
        while ($row = mysqli_fetch_assoc($cur)) {
            if ($key) {
                $array[$row[$key]] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysqli_free_result($cur);
        return $array;
    }

    public function loadObject($sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        if (!($cur = $this->query())) {
            return null;
        }
        $ret = null;
        if ($object = mysqli_fetch_object($cur)) {
            $ret = $object;
        }
        mysqli_free_result($cur);
        return $ret;
    }

    public function loadObjectList($key = '', $sql = '')
    {
        if ($sql != '') {
            $this->sql = $sql;
        }

        if (!($cur = $this->query())) {
            return null;
        }
        $array = [];
        while ($row = mysqli_fetch_object($cur)) {
            if ($key) {
                $array[$row->$key] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysqli_free_result($cur);
        return $array;
    }

    public function disconnect()
    {
        mysqli_close($this->connection);
    }


    public function quoteName($name, $as = null)
    {
        if (\is_string($name)) {
            $name = $this->quoteNameString($name);

            if ($as !== null) {
                $name .= ' AS ' . $this->quoteNameString($as, true);
            }

            return $name;
        }

        $fin = [];

        if ($as === null) {
            foreach ($name as $str) {
                $fin[] = $this->quoteName($str);
            }
        } elseif (\is_array($name) && (\count($name) === \count($as))) {
            $count = \count($name);

            for ($i = 0; $i < $count; $i++) {
                $fin[] = $this->quoteName($name[$i], $as[$i]);
            }
        }

        return $fin;
    }

    protected function quoteNameString($name, $asSinglePart = false)
    {
        $q = $this->nameQuote . $this->nameQuote;

        // Double quote reserved keyword
        $name = str_replace($q[1], $q[1] . $q[1], $name);

        if ($asSinglePart) {
            return $q[0] . $name . $q[1];
        }

        return $q[0] . str_replace('.', "$q[1].$q[0]", $name) . $q[1];
    }
}
