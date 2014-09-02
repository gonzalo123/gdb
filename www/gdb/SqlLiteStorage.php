<?php

use Doctrine\DBAL\Connection;
use G\Io\Storage\StorageIface;

class SqlLiteStorage implements StorageIface
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get($key)
    {
        $statement = $this->db->executeQuery("select value from storage where key = :KEY", ['KEY' => $key]);
        $data      = $statement->fetchAll();

        return isset($data[0]['value']) ? $data[0]['value'] : null;
    }

    public function post($key, $value)
    {
        $this->db->beginTransaction();

        $statement = $this->db->executeQuery("select value from storage where key = :KEY", ['KEY' => $key]);
        $data      = $statement->fetchAll();

        if (count($data) > 0) {
            $this->db->update('storage', ['value' => $value], ['key' => $key]);
        } else {
            $this->db->insert('storage', ['key' => $key, 'value' => $value]);
        }

        $this->db->commit();

        return $value;
    }
}