<?php

namespace Database;

class MySQL
{
    protected $hostname;
    protected $dbUsername;
    protected $dbPassword;
    protected $dbName;
    protected $connection;
    protected $tableName;

    public function __construct($hostname, $dbName, $dbUsername, $dbPassword, $tableName = null)
    {
        $this->hostname = $hostname;
        $this->dbUsername = $dbUsername;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
        $this->tableName = $tableName;
        $this->connection = new \PDO("mysql:host=$hostname;dbname=$dbName;charset=utf8mb4;", $dbUsername, $dbPassword);
        $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

    }

    public function use()
    {
        return $this->connection;
    }

    public function setTable($tableName)
    {
        $this->tableName = $tableName;
    }

    private function separateParams($params): array
    {
        $data = [];
        foreach ($params as $key => $value) {
            if($key != 'id' && $value != '') {
                $data['keys'][] = $key;
                $data['fields'][] = $key . '=:' . $key;
                $data['values'][] = ':' . $key;
                $data['setValues'][$key] = $value;
            }
        }
        return $data;
    }

    private function formatId($id): int
    {
        return intval($id);
    }

    public function create(array $params)
    {
        $paramData = $this->separateParams($params);
        $sql = 'INSERT INTO ' . $this->tableName . '  (' . implode(',', $paramData['keys']) . ') VALUES (' . implode(',', $paramData['values']) . ')';
        return $this->connection->prepare($sql)->execute($paramData['setValues']);
    }

    public function read(int $param)
    {
        $id = $this->formatId($param);
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE id = :id';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id',$id);
        $db->execute();
        $result = $db->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function update(int $id, array $params)
    {
        $id = $this->formatId($id);
        $paramData = $this->separateParams($params);
        $sql = 'UPDATE  ' . $this->tableName . ' SET ' . implode(', ', $paramData['fields']) . ' WHERE id=:id';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id',$id);
        foreach($paramData['setValues'] as $key => $value){
            $db->bindParam(':'.$key, $value);
        }
        return $db->execute();
    }

    public function delete(int $id)
    {
        $id = $this->formatId($id);
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id=:id';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id',$id);
        $result = $db->execute();
        return $result;
    }
}