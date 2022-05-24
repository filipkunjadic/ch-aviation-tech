<?php

include './Database/MySQL.php';

class User
{
    protected $db;

    public function __construct()
    {
        $keys = [
            'DB_HOSTNAME' => 'localhost',
            'DB_NAME' => 'tech',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => ''
        ];
        $this->db = new Database\MySQL($keys['DB_HOSTNAME'], $keys['DB_NAME'], $keys['DB_USERNAME'], $keys['DB_PASSWORD']);
        $this->db->setTable('users');
    }

    public function create(array $data): bool
    {
        return $this->db->create($data);
    }

    public function read(int $id): mixed
    {
        return $this->db->read($id);
    }

    public function update($id, $data): bool
    {
        return $this->db->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->db->delete($id);
    }
}