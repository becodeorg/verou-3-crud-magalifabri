<?php

class CardRepository
{
    private DatabaseManager $databaseManager;

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
        $query = 'SELECT *
            FROM pokemon
            WHERE is_deleted IS NULL
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
                last_update = NOW()
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
            SET is_deleted = 1
            WHERE id = :id;';

        // hard delete query
        // $query = 'DELETE FROM pokemon
        //     WHERE id = :id;';

        $stmt = $this->databaseManager->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
