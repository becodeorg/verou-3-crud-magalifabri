<?php

class DatabaseManager
{
    private string $scheme;
    private string $host;
    private int $port;
    private string $user;
    private string $password;
    private string $dbname;

    public PDO $connection;

    public function __construct(string $scheme, string $host, int $port, string $user, string $password, string $dbname)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    public function connect(): void
    {
        try {
            $this->connection = new PDO(
                "{$this->scheme}:
                    host={$this->host};
                    port={$this->port};
                    dbname={$this->dbname}",
                $this->user,
                $this->password
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
