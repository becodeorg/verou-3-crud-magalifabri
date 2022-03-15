<?php

// This class is focussed on dealing with queries for one type of data
// That allows for easier re-using and it's rather easy to find all your queries
// This technique is called the repository pattern
class CardRepository
{
    private DatabaseManager $databaseManager;

    // This class needs a database connection to function
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function create(): void
    {
    }

    // Get one
    public function find(): array
    {
    }

    // Get all
    public function get(): array
    {
        // make and run prepared statement
        $query = 'SELECT *
            FROM pokemon';
        $stmt = $this->databaseManager->connection->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return ($rows);
    }

    public function update(): void
    {
    }

    public function delete(): void
    {
    }
}
