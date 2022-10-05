<?php


namespace Source\Core;

use Source\Support\Message;

/**
 * Class Model
 * @package Source\Models
 */
abstract class Model
{
    /** @var object|null */
    protected $data;

    /** @var ?\PDOException */
    protected $fail;

    /** @var Message|null */
    protected $message;

    /** @var string */
    protected $query;

    /** @var string */
    protected $params;

    /** @var string */
    protected $join;

    /** @var string */
    protected $order;

    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    /** @var string */
    protected $having;


    /** @var string $entity database table */
    protected $entity;

    /** @var array $protected no update or create */
    protected $protected;

    /** @var array $required database table */
    protected $required;


    /**
     * Model constructor.
     * @param string $entity
     * @param array $protected
     * @param array $required
     */
    public function __construct(string $entity, array $protected, array $required)
    {
        $this->entity = $entity;
        $this->protected = array_merge($protected, ["created_at", "updated_at"]);
        $this->required = $required;
        $this->message = new Message();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }
        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @param $name
     * @return |null
     */
    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    /**
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return \PDOException
     */
    public function fail(): ?\PDOException
    {
        return $this->fail;
    }

    /**
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * @param array $data
     * @return int|null
     */
    protected function create(array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $stmt = Connect::getInstance()->prepare("INSERT INTO " . $this->entity . " ({$columns}) VALUES ({$values})");
            $stmt->execute($this->filter($data));

            return Connect::getInstance()->lastInsertId();

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     * @param string $columns
     * @return \Source\Core\Lead\Model | mixed
     */
    public function find(?string $terms = null, ?string $params = null, string $columns = "*")
    {
        if ($terms) {
            $this->query = "SELECT {$columns} FROM " . $this->entity . " WHERE {$terms}";
            parse_str($params, $this->params);
            return $this;
        }
        $this->query = "SELECT {$columns} FROM " . $this->entity;
        return $this;
    }


    /**
     * @param int $id
     * @param string $columns
     * @return null|mixed|Model
     */
    public function findById(int $id, string $columns = "*"): ?Model
    {
        $find = $this->find("id = :id", "id={$id}", $columns);
        return $find->fetch();
    }


    /**
     * @param string $table
     * @param string $on
     * @return Model
     */
    public function innerJoin(string $table, string $on): Model
    {
        $this->join .= "INNER JOIN {$table} ON {$on} ";
        return $this;
    }

    /**
     * @param string $table
     * @param string $on
     * @return Model
     */
    public function leftJoin(string $table, string $on): Model
    {
        $this->join .= "LEFT JOIN {$table} ON {$on} ";
        return $this;
    }

    /**
     * @param string $table
     * @param string $on
     * @return Model
     */
    public function rightJoin(string $table, string $on): Model
    {
        $this->join .= "RIGHT JOIN {$table} ON {$on} ";
        return $this;
    }

    /**
     * @param bool $all
     * @return array|mixed|Model|null
     */
    public function fetchJoin(bool $all = false, int $afterwhere = 0)
    {
        if (!empty($this->join)) {

                $before = substr($this->query, 0, strrpos($this->query, "WHERE"));
                for($i = 0 ; $i < $afterwhere ;$i++){
                    $before = substr($before, 0, strrpos($before, "WHERE"));
                }

                //$after = strrpos($this->query, "WHERE");
                //$after = substr($this->query,0,$after);

                $aux = $this->query;

                for($i = 0 ; $i <= $afterwhere ;$i++){
                    $after = strrpos($aux, "WHERE");
                  //  var_dump($after);
                    $aux = substr($this->query,0,$after);
                   // var_dump($aux);
                    $after = substr($this->query,$after);
                   // var_dump($after);
                }


            $this->query = $before . "AS m " . $this->join . $after;

//            echo <<<EOT
// $this->query  $this->having  $this->order  $this->limit  $this->offset;
// EOT;

        }
        return $this->fetch($all);
    }


    /**
     * @param string|null $params
     * @return $this
     */
    public function procedure(?string $params = '')
    {
        $this->query = "CALL " . $this->entity . " {$params}";
        return $this;
    }

    /**
     * @param string $columnOrder
     * @return Model
     */
    public function order(string $columnOrder): Model
    {
        $this->order = " ORDER BY {$columnOrder}";
        return $this;
    }

    /**
     * @param int $limit
     * @return Model
     */
    public function limit(int $limit): Model
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * @param int $offset
     * @return Model
     */
    public function offset(int $offset): Model
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * @param string $having
     * @return Model
     */
    public function having(string $having): Model
    {
        if(!empty($having)) {
            $this->having = " HAVING {$having}";
        }
        return $this;
    }

    /**
     * @param bool $all
     * @return null|array|mixed|Model
     */
    public function fetch(bool $all = false)
    {

        try {
            $stmt = Connect::getInstance()->prepare($this->query . $this->having. $this->order . $this->limit . $this->offset);
           // var_dump($stmt);
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($all) {
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }
            return $stmt->fetchObject(static::class);

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $key
     * @return int
     */
    public function count(string $key = 'id'): int
    {

        $stmt = Connect::getInstance()->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }


    /**
     * @param array $data
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    protected function update(array $data, string $terms, string $params): ?int
    {
        try {
            $dataSet = [];
            foreach ($data as $bind => $value) {
                $dataSet[] = "{$bind} = :{$bind}";
            }

            $dataSet = implode(", ", $dataSet);
            parse_str($params, $params);

            $stmt = Connect::getInstance()->prepare("UPDATE " . $this->entity . " SET {$dataSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $params)));

            return ($stmt->rowCount() ?? 1);

        } catch (\PDOException $exception) {
            $this->fail = $exception;
              return null;
        }
    }


    /**
     * @param string $terms
     * @param null|string $params
     * @return bool
     */
    public function delete(string $terms, ?string $params): bool
    {
        try {
            $stmt = Connect::getInstance()->prepare("DELETE FROM " . $this->entity . " WHERE {$terms}");
            if ($params) {
                parse_str($params, $params);
                $stmt->execute($params);
                return true;
            }

            $stmt->execute();
            return true;
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return false;
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->required()) {
            $this->message->warning("Preencha todos os campos para continuar");
            return false;
        }

        if (!empty($this->id)) {
            $id = $this->id;
            $this->update($this->safe(), "id = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        /** Create */
        if (empty($this->id)) {
            $id = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = $this->findById($id)->data();
        return true;
    }


    /**
     * @return bool
     */
    public function destroy(): bool
    {
        if (empty($this->id)) {
            return false;
        }
        $destroy = $this->delete("id = :id", "id={$this->id}");
        return $destroy;
    }


    /**
     * @return array|null
     */
    protected function safe(): ?array
    {
        $safe = (array)$this->data;
        foreach ($this->protected as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * @param array $data
     * @return array|null
     */
    protected function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }

    /**
     * @return bool
     */
    protected function required(): bool
    {
        $data = (array)$this->data();
        //var_dump($data);
        foreach ($this->required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }
}