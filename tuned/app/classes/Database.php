<?php

class Database
{
    private mysqli $connection;

    public function __construct(array $config)
    {
        $this->connection = new mysqli(
            $config['host'],
            $config['user'],
            $config['password'],
            $config['database']
        );

        if ($this->connection->connect_error) {
            throw new RuntimeException('Database connection failed: ' . $this->connection->connect_error);
        }

        $this->connection->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
