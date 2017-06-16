<?php

class Database extends PDO
{
    private $host = DB_HOST;
    private $port = DB_PORT;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_DBNAME;

    private $_db;
    private $stmt;
    private $error;

    public function __construct()
    {
        $dsn = 'pgsql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname;
        $options = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

        try {
            $this->_db = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->error = $e->getMessage();
        }
    }

    public function query($sql)
    {
        $this->stmt = $this->_db->prepare($sql);
    }

    public function bind($params_arr)
    {
        foreach ($params_arr as $param => $value) {
            $type = isset($p['type']) ? $p['type'] : NULL;

            if (is_null($type)) {
                switch (true) {
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;

                    default:
                        $type = PDO::PARAM_STR;
                }
            }
            $this->stmt->bindValue($param, $value, $type);
        }
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function findAll()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

//    public function checkExists($tableName, $data, $param)
//    {
//        $newData = $this->convertKeysArrayToLower($data);
//        $newParam = $this->createConditionOR($param);
//        $sql = "SELECT * FROM $tableName WHERE $newParam";
//        $this->query($sql);
//        $data = $this->findAll();
//        if ($data['token'] === NULL || $data['token'] === '') {
//            return false;
//        } else {
//            return true;
//        }
//    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId($seq_name = null)
    {
        return $this->_db->lastInsertId($seq_name);
    }

    public function convertKeysArrayToLower($data)
    {
        $keysData = array_keys($data);
        $valsData = array_values($data);
        for ($i = 0; $i < count($keysData); $i++) {
            if ($keysData[$i] === 'id') {
                $keysData[$i] = 'customerid';
            }
        }
        for ($i = 0; $i < count($keysData); $i++) {
            $newData[$keysData[$i]] = $valsData[$i];
        }
        return $newData;
    }

    /**
     * @param $tableName
     * @param $data
     * @return bool
     */
    public function insert($tableName, $data)
    {
        $fields = array_keys($data);
        for ($i = 0; $i < count($fields); $i++) {
            strtolower($fields[$i]);
        }
        $params = ':' . implode(',:', $fields);
        $fields = implode(',', $fields);
        $sql = "INSERT INTO $tableName ($fields) VALUES ($params)";
        $this->query($sql);
        $data = $this->renameKey($data, ':');
        $this->bind($data);
        try {
            $this->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return true;
    }

    /**
     * @param $tableName
     * @param $data
     * @param $where
     * @return bool
     */
    public function update($tableName, $data, $where)
    {
        $fields = array_keys($data);
        $fields2 = array_keys($where);
        $query = $this->createQuery($fields);
        $condition = $this->createCondition($fields2);
        $sql = "UPDATE $tableName SET $query WHERE $condition";
        $this->query($sql);
        $data = $this->renameKey($data, ':');
        $where = $this->renameKey($where, ':_');
        $this->bind($data);
        $this->bind($where);
        try {
            $this->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return true;
    }

    /**
     * @param $tableName
     * @param $where
     * @return bool
     */
    public function delete($tableName, $where)
    {
        $condition = $this->createConditionOR($where);
        $sql = "DELETE FROM $tableName WHERE $condition";
        $this->query($sql);
        $where = $this->renameKey($where, ':_');
        $this->bind($where);
        try {
            $this->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return true;
    }

    private function createQuery($fields)
    {
        $query = [];
        for ($i = 0; $i < count($fields); $i++) {
            $query[] = $fields[$i] . '=:' . $fields[$i];
        }
        return implode(',', $query);
    }

    private function createCondition($fields)
    {
        $query = [];
        for ($i = 0; $i < count($fields); $i++) {
            $query[] = $fields[$i] . '=:_' . $fields[$i];
        }
        return implode(',', $query);
    }

    private function createConditionOR($fields)
    {
        // Array $fields remove $k have $v == NULL or $v == ''
        foreach ($fields as $k => $v) {
            if ($v != NULL || $v != '') {
                $arr[$k] = $v;
            }
        }
        $arr = array_keys($arr);
        $query = [];
        for ($i = 0; $i < count($arr); $i++) {
            $query[] = $arr[$i] . '=:_' . $arr[$i];
        }
        return implode(' OR ', $query);
    }

    private function renameKey($arr, $prefix)
    {
        foreach ($arr as $key => $value) {
            $arr[$prefix . $key] = $value;
            unset($arr[$key]);
        }
        return $arr;
    }

}