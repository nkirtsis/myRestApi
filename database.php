<?php

/**
 * database class
 *
 * The class that implements all the actions with the database.
 *
 * @author Nikos Kirtsis <nkirtsis@gmail.com>
 * @copyright 2015 Nikos Kirtsis
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class database
{

    const DB_SERVER      = "localhost";
    const DB_USER        = "root";
    const DB_PASSWORD    = "";
    const DB             = "sociomantic";

    protected $connection;

    public function __construct()
    {
        $this->connection = new mysqli(
                                        self::DB_SERVER,
                                        self::DB_USER,
                                        self::DB_PASSWORD,
                                        self::DB
                                      );

        return $this->connection;
    }

    public function selectStatement($table, $filters, $attrs = '*')
    {
        if ($attrs != '*' && count($attrs) > 1) {
            $attrs = implode(',', $attrs);
        }
        if (count($filters) == 1) {
            return    "SELECT " . $attrs . " FROM " . $table .
                    " WHERE " . $this->arrayToCommaSeparatedSyntax($filters);
        } else {
            return "SELECT " . $attrs . " FROM " . $table;
        }
    }

    public function query($sql)
    {
        if ($result = $this->connection->query($sql)) {
            return $result;
        } else {
            echo $this->connection->error;
        }
    }

    public function fetch($result)
    {
        $rows = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    public function performSelect($selectStatement)
    {
        return $this->fetch($this->query($selectStatement));
    }

    public function getFieldValue($id, $field)
    {
        $selectField = $this->selectStatement('creatures', ['creature_id'=>$id], $field);
        $rows        = $this->performSelect($selectField);

        return $rows[0][$field];
    }

    public function insertStatement($table, $attrs)
    {
        $columns = implode(',', array_keys($attrs));
        $values  = '"' . implode('","', array_values($attrs)) . '"' ;

        return    "INSERT INTO " . $table . " (" . $columns . ") " .
                "VALUES (" . $values . ")";
    }

    public function updateStatement($table, $attrs, $id)
    {
        return    "UPDATE "    . $table .
                " SET "    . $this->arrayToCommaSeparatedSyntax($attrs) .
                " WHERE "    . $this->getPrimaryKey($table) . "=" . $id;
    }

    public function deleteStatement($table, $id)
    {
        return    "DELETE FROM " . $table .
                " WHERE " . $this->getPrimaryKey($table) . "=" . $id;
    }

    public function disconnect()
    {
        $this->connection->close();
    }

    private function getPrimaryKey($table)
    {
        $sql    = "SHOW KEYS FROM " . $table . " WHERE Key_name = 'PRIMARY'";
        $result = $this->connection->query($sql);
        $row    = $result->fetch_assoc();

        return $row['Column_name'];
    }

    private function arrayToCommaSeparatedSyntax($array)
    {
        $pairs = [];
        foreach ($array as $key => $val) {
            $pairs[] = $key . '="' . $val . '"';
        }

        return implode(',', $pairs);
    }
}
