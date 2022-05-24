<?php

namespace Database;

class MySQL
{

    protected \PDO $connection;
    protected mixed $tableName;

    public function __construct($tableName = null)
    {
        $keys = include './keys.php';
        $this->tableName = $tableName;
        $this->connection = new \PDO("mysql:host=" . $keys['DB_HOSTNAME'] . ";dbname=" . $keys['DB_NAME'] . ";charset=utf8mb4;", $keys['DB_USERNAME'], $keys['DB_PASSWORD']);
        $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function use(): \PDO
    {
        return $this->connection;
    }

    public function setTable($tableName): void
    {
        $this->tableName = $tableName;
    }

    private function separateParams($params): array
    {
        $data = [];
        foreach ($params as $key => $value) {
            if ($key != 'id' && $value != '') {
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

    public function run($sql): bool
    {
        try {
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->exec($sql);
            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function create(array $params): bool|string|null
    {
        $paramData = $this->separateParams($params);
        $sql = 'INSERT INTO ' . $this->tableName . '  (' . implode(',', $paramData['keys']) . ') VALUES (' . implode(',', $paramData['values']) . ')';
        $db = $this->connection->prepare($sql);
        $result = $db->execute($paramData['setValues']);
        return $result ? $this->connection->lastInsertId() : null;

    }

    public function read(int $param): mixed
    {
        $id = $this->formatId($param);
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE id = :id';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, \PDO::PARAM_INT);
        $db->execute();
        return $db->fetch(\PDO::FETCH_ASSOC);
    }

    public function update(int $id, array $params): bool
    {
        $id = $this->formatId($id);
        $paramData = $this->separateParams($params);
        $sql = 'UPDATE  ' . $this->tableName . ' SET ' . implode(', ', $paramData['fields']) . ' WHERE id=:id';
        $db = $this->connection->prepare($sql);
        foreach ($paramData['setValues'] as $key => &$value) {
            $db->bindParam($key, $value);
        }
        $db->bindParam(':id', $id);
        return $db->execute();
    }

    public function delete(int $id): ?int
    {
        $id = $this->formatId($id);
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id=:id';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, \PDO::PARAM_INT);
        $result = $db->execute();
        return $result ? $db->rowCount() : null;
    }
}