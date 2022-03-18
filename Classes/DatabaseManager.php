<?php

class DatabaseManager
{
    private string $host;
    private string $user;
    private string $password;
    private string $dbname;

    public PDO $connection;

    public function __construct(string $host, string $user, string $password, string $dbname)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    public function connect(): void
    {
        try {
            if (!empty(getenv("DATABASE_URL"))) {
                $dbParams = parse_url(getenv("DATABASE_URL"));

                $this->connection = new PDO("pgsql:" . sprintf(
                    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
                    $dbParams["host"],
                    $dbParams["port"],
                    $dbParams["user"],
                    $dbParams["pass"],
                    ltrim($dbParams["path"], "/")
                ));
            } else {
                $DSN = "mysql:host={$this->host};dbname={$this->dbname}";
                $this->connection = new PDO($DSN, $this->user, $this->password);
            }

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
