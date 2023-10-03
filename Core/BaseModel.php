<?php
/**
 * Nom du fichier : BaseModel.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

namespace Core;

use Core\Database\QueryBuilder;
use App\Exceptions\BaseModelException;

class BaseModel
{
    protected ?string $table = null;

    public array $fields = [];

    protected array $data = [];

    protected QueryBuilder $queryBuilder;

    /**
     * BaseModel constructor.
     */
    public function __construct()
    {
        if( is_null($this->table) ) {
            $model = explode('\\', get_class($this));
            $className = end($model);
            $this->table = Config::getInstance()->get('database.prefix') . strtolower(str_replace('Model', '', $className)) . 's';
        }

        //  TODO: Gérer la sélection des champs à récupérer
        $this->queryBuilder = new QueryBuilder();
        $this->queryBuilder->select()->from($this->table);

        $this->fields = array_fill_keys($this->fields, null);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $results = $this->queryBuilder->execute()->fetchAll();
        return $this->collection($results);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        $result = $this->queryBuilder
            ->where('id', '=' ,':id')
            ->execute()
            ->fetch();

        if( $result ) {
            $this->hydrate((array) $result);
            return (object) $this->getModelFields($this);
        }

        return null;
    }

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        $fields = array_keys($data);
        $placeholders = array_map(fn($item) => ":{$item}", $fields);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(", ", $fields),
            implode(", ", $placeholders)
        );

        $this->queryBuilder->executeRaw($sql, $data);
        $this->data['id'] = $this->queryBuilder->lastInsertId();
        $this->data['created_at'] = date('Y-m-d H:i:s');
        $this->data['updated_at'] = date('Y-m-d H:i:s');
        $this->hydrate($data);

        return $this->getModelFields($this);
    }

    /**
     * @param int $id
     * @param array $data
     * @return object
     */
    public function update(int $id, array $data): object
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $fields = array_keys($data);
        $placeholders = array_map(fn($item) => "{$item} = :{$item}", $fields);

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->table,
            implode(", ", $placeholders)
        );

        $data['id'] = $id;
        $this->queryBuilder->executeRaw($sql, $data);
        $this->hydrate($data);

        return $this->getModelFields($this);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = sprintf(
            "DELETE FROM %s WHERE id = :id",
            $this->table
        );

        $this->queryBuilder->executeRaw($sql, ['id' => $id]);

        return true;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return $this
     * @throws \Exception
     */
    public function where(string $column, string $operator, mixed $value): self
    {
        if( in_array($operator, ['=', '!=', '>', '<', '>=', '<=']) ) {
            throw new BaseModelException('Opérateur non autorisé');
        }

        $this->queryBuilder->where($column, $operator, $value);

        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset = 0): self
    {
        $this->queryBuilder->limit($limit, $offset);
        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->queryBuilder->orderBy($field, $direction);
        return $this;
    }

    /**
     * @return $this
     */
    public function latest(): self
    {
        $this->queryBuilder->orderBy('created_at', 'DESC');
        return $this;
    }

    /**
     * @return $this
     */
    public function oldest(): self
    {
        $this->queryBuilder->orderBy('created_at', 'ASC');
        return $this;
    }

    /**
     * @return array|null
     */
    public function first(): ?object
    {
        $result = $this->queryBuilder->limit(1)->execute()->fetch();
        if( $result ) {
            $this->hydrate((array) $result);
            return $this->getModelFields($this);
        }

        return null;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $results = $this->queryBuilder->execute()->fetchAll();
        return $this->collection($results);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getAttribute($name): mixed
    {
        return $this->data[$name] ?? null;
    }

    public function setData($key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @param array $data
     * @return void
     */
    protected function hydrate(array $data): void
    {
        if (isset($data['id'])) {
            //$this->data['id'] = $data['id'];
            $this->setData('id', $data['id']);
        }

        foreach ($this->fields as $field => $value) {
            if( isset($data[$field]) ) {
                $this->setData($field, $data[$field]);
            }
        }
    }

    /**
     * @param $results
     * @return array
     */
    public function collection($results): array
    {
        return array_map(function($item) {
            $model = new static();
            $model->hydrate((array) $item);
            return $this->getModelFields($model);
        }, $results);
    }

    /**
     * @param $model
     * @return object
     */
    public function getModelFields($model): object
    {
        return (object) $model->data;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return object
     */
    public function save(): object
    {
        if( isset($this->data['id']) ) {
            return $this->update($this->data['id'], $this->data);
        } else {
            return $this->create($this->data);
        }
    }
}