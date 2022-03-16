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

    public function create(array $params): void
    {
        $query = 'INSERT INTO pokemon (pokemon, nickname, level)
        VALUES (:pokemon, :nickname, :level);';
        $stmt = $this->databaseManager->connection->prepare($query);
        $stmt->bindParam(':pokemon', $params['pokemon']);
        $stmt->bindParam(':nickname', $params['nickname']);
        $stmt->bindParam(':level', $params['level']);
        $stmt->execute();
    }

    // Get one
    public function find($id): array
    {
        $query = 'SELECT *
            FROM pokemon
            WHERE id = :id';
        $stmt = $this->databaseManager->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return ($rows[0]);
    }

    // Get range
    public function get($from, $to): array
    {
        // make and run prepared statement
        $query = 'SELECT *
            FROM pokemon
            WHERE isDeleted IS NULL
                AND level >= :from
                AND level <= :to;';
        $stmt = $this->databaseManager->connection->prepare($query);
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':to', $to);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return ($rows);
    }

    public function update($params): void
    {
        $query = 'UPDATE pokemon
            SET
                pokemon = :pokemon,
                nickname = :nickname,
                level = :level,
                lastUpdate = NOW()
            WHERE id = :id';
        $stmt = $this->databaseManager->connection->prepare($query);
        $stmt->bindParam(':id', $params['id']);
        $stmt->bindParam(':pokemon', $params['pokemon']);
        $stmt->bindParam(':nickname', $params['nickname']);
        $stmt->bindParam(':level', $params['level']);
        $stmt->execute();
    }

    public function delete($id): void
    {
        // soft delete query
        $query = 'UPDATE pokemon
            SET isDeleted = 1
            WHERE id = :id;';

        // hard delete query
        // $query = 'DELETE FROM pokemon
        //     WHERE id = :id;';

        $stmt = $this->databaseManager->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
