<?php
/**
 * Nom du fichier : QueryBuilder.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

namespace Core\Database;

use App\App;

class QueryBuilder
{
    protected \PDO $connection;

    protected string $query = '';

    protected array $bindings = [];

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance();
    }

    /**
     * @param string $fields
     * @return $this
     */
    public function select(string $fields = '*'): self
    {
        $this->query .= "SELECT {$fields}";

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function from(string $table): self
    {
        $this->query .= " FROM {$table}";

        return $this;
    }

    /**
     * @param string $field
     * @param string $operator
     * @param $value
     * @return $this
     */
    public function where(string $field, string $operator, $value): self
    {
        $this->query .= " WHERE {$field} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * @param string $field
     * @param string $order
     * @return $this
     */
    public function orderBy(string $field, string $order = 'ASC'): self
    {
        $this->query .= " ORDER BY {$field} {$order}";

        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset = 0): self
    {
        $this->query .= " LIMIT {$limit} OFFSET {$offset}";
        return $this;
    }

    /**
     * @return \PDOStatement
     */
    public function execute(): \PDOStatement
    {
        $statement = $this->connection->prepare($this->query);
        try {
            $statement->execute($this->bindings);
        } catch( \PDOException $e ) {
            die("Erreur de requête SQL : " . $e->getMessage());
        }

        return $statement;
    }

    /**
     * @param string $sql
     * @param array $bindings
     * @return \PDOStatement
     */
    public function executeRaw(string $sql, array $bindings = []): \PDOStatement
    {
        $statement = $this->connection->prepare($sql);
        try {
            $statement->execute($bindings);
        } catch( \PDOException $e ) {
            die("Erreur de requête SQL : " . $e->getMessage());
        }

        return $statement;
    }

    /**
     * @return int
     */
    public function lastInsertId(): int
    {
        return (int)$this->connection->lastInsertId();
    }
}