<?php

namespace App\Database;

use App\Services\AppService;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Orkester\Persistence\Map\ClassMap;

class Criteria extends Builder
{
//    public string|Model $model;
//    public ClassMap $classMap;
//    public $fieldAlias = [];
//    public $tableAlias = [];
//    public Set $generatedAliases;
//    public $classAlias = [];
//    public $criteriaAlias = [];
//    public $associationJoin = [];
//    public $associationAlias = [];
//    public $associationClass = [];
//    static public $aliasCount = 0;
//    public $parameters = [];
//    public $aliasTable = NULL;
    /**
     * //     * @var ClassMap[] $maps
     */
//    private array $maps;

    public function __construct()
    {
        $connection = app()->make(ConnectionInterface::class);
        parent::__construct($connection);
    }

    public static function table(string $tableName): static
    {
        $self = new self();
        $self->from($tableName);
        return $self;
    }

    public static function byFilter(string $tableName, array $filter): static
    {
        $self = new self();
        return $self->from($tableName)
            ->filter($filter);
    }

    public static function byFilterLanguage(string $tableName, array $filter, ?string $languageColumn = null, ?int $idLanguage = null): static
    {
        $languageColumn ??= 'idLanguage';
        $idLanguage ??= AppService::getCurrentIdLanguage();
        $self = new self();
        return $self->from($tableName)
            ->filter($filter)
            ->where($languageColumn, '=', $idLanguage);
    }

    public static function byId(string $tableName, string $key, mixed $value): null|object
    {
        $self = new self();
        return $self->from($tableName)
            ->where($key, $value)
            ->first();
    }

    public static function deleteById(string $tableName, string $key, mixed $value): void
    {
        $self = new self();
        $self->from($tableName)
            ->where($key, $value)
            ->delete();
    }

    public static function one(string $tableName, array $filter): null|object
    {
        $self = new self();
        return $self->from($tableName)
            ->filter($filter)
            ->first();
    }

    public static function create(string $tableName, array $values): ?int
    {
        $self = new self();
        $self->from($tableName)
            ->insert($values);
        return $self->getConnection()->getPdo()->lastInsertId();
    }

    public static function call(string $routine, array $params): mixed
    {
        return DB::select("call {$routine}", $params);
    }

    public static function function (string $routine, array $params): mixed
    {
        $result = DB::select("select {$routine} as result", $params);
        return $result[0]->result;
    }

    public static function var(string $var): mixed
    {
        $result = DB::select("select {$var}");
        return $result[0]->{$var};
    }

    public function filter(array $filter): Criteria
    {
        if (!empty($filter)) {
            $filter = is_string($filter[0]) ? [$filter] : $filter;
            foreach ($filter as [$field, $op, $value]) {
                if (!is_null($value)) {
                    $this->where($field, $op, $value);
                }
            }
        }
        return $this;
    }

    /**
     * @param string|null $className
     * @return array array<$className>
     */
    public function all(): array
    {
        return $this->get()->all();
    }

    public function chunkResult(string $fieldKey = '', string $fieldValue = ''): array
    {
        return $this->get()->pluck($fieldValue, $fieldKey)->all();
    }

    public function treeResult(string $groupBy): Collection
    {
        return $this->get()
            ->groupBy($groupBy);
    }

    public function keyBy(string $field): Collection
    {
        return $this->get()
            ->keyBy($field);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and'): static
    {
        if (func_num_args() > 2) {
            $uOp = strtoupper($operator ?? "");
            if ($uOp == 'STARTSWITH') {
                $operator = 'LIKE';
                $value = $value . '%';
            } elseif ($uOp == 'CONTAINS') {
                $operator = 'LIKE';
                $value = '%' . $value . '%';
            }
            $uValue = is_string($value) ? strtoupper($value) : $value;
            if (($uValue === 'NULL') || is_null($value)) {
                $this->whereNull($column);
            } else if ($uValue === 'NOT NULL') {
                $this->whereNotNull($column);
            } else if ($uOp === 'IN') {
                $this->whereIn($column, $value);
            } else if ($uOp === 'NOT IN') {
                $this->whereNotIn($column, $value);
            } else {
                parent::where($column, $operator, $value, $boolean);
            }
        } else {
            parent::where($column, $operator, $value, $boolean);
        }
        return $this;
    }


//    public function __construct(ConnectionInterface $connection)
//    {
//        $grammar = match (get_class($connection->getQueryGrammar())) {
//            \Illuminate\Database\Query\Grammars\MySqlGrammar::class, MariaDbGrammar::class => new MySqlGrammar($this),
//            \Illuminate\Database\Query\Grammars\SQLiteGrammar::class => new SQLiteGrammar($this),
//            default => throw new \InvalidArgumentException("Unknown database grammar")
//        };
//        $grammar->setConnection($connection);
//        parent::__construct($connection, $grammar);
//        $this->generatedAliases = new Set();
//    }
//
//    public function setClassMap(ClassMap $classMap)
//    {
//        $this->classMap = $classMap;
//        $this->model = $classMap->model;
//        $this->maps[$this->model] = $classMap;
//        $this->connection->table($this->tableName());
//        $this->from($this->tableName());
//        return $this;
//    }
//
//    public function tableName(string $className = '')
//    {
//        if ($className != '') {
//            $this->registerClass($className);
//            $tableName = $this->maps[$className]->tableName;
//        } else {
//            $tableName = $this->maps[$this->model]->tableName;
//        }
//        return $tableName;
//    }
//
//    protected function registerClass($className)
//    {
//        if (!isset($this->maps[$className])) {
//            $this->addMapFor($className);
//        }
//    }
//
//    public function addMapFor(string $className)
//    {
//        $classMap = PersistenceManager::getClassMap($className);
//        $this->maps[$className] = $classMap;
//    }
//
//    public function getModel()
//    {
//        return $this->model;
//    }
//
//    public function setModel(string $model)
//    {
//        debug($model);
//        $this->classMap = PersistenceManager::getClassMap($model);
//        $this->model = $model;
//        $this->maps[$this->model] = $this->classMap;
//        $this->connection->table($this->tableName());
//        $this->from($this->tableName());
//        return $this;
//    }
//
//    public function newQuery()
//    {
//        return (new static($this->connection))->setModel($this->model);
//    }
//
//    public function columnName(string $className, string $attribute)
//    {
//        //mdump('attribute to column = ' . $className . '.' . $attribute . PHP_EOL);
//        //return ($attribute == '*') ? '*' : $this->maps[$className ?: $this->model]->getAttributeMap($attribute)?->columnName ?? $attribute;
//        return ($attribute == '*') ? '*' : $this->getAttributeMap($attribute, $className ?: $this->model)?->columnName ?? $attribute;
//    }
//
//    public function getAttributeMap(string $attributeName, $className = ''): ?AttributeMap
//    {
//        $mapName = $this->model;
//        if ($className != '') {
//            $this->registerClass($className);
//            $mapName = $className;
//        }
//        if ($attributeName == 'id') {
//            $attributeName = $this->maps[$mapName]->keyAttributeName;
//        }
//        return $this->maps[$mapName]->getAttributeMap($attributeName);
//    }
//
//    public function getAssociationMap($associationName, $className = ''): ?AssociationMap
//    {
////        mdump('getAssociationMap  className: ' . ($className != '' ? $className : $this->model) . '.' . $associationName . PHP_EOL);
//        if ($className != '') {
//            $this->registerClass($className);
//            $associationMap = $this->maps[$className]->getAssociationMap($associationName);
//        } else {
//            $associationMap = $this->maps[$this->model]->getAssociationMap($associationName);
//        }
//        return $associationMap;
//    }
//
//    public function setAssociationType(string $associationName, Join $type): Criteria
//    {
//        $this->associationJoin[$associationName] = $type;
//        return $this;
//    }
//
//    public function setAssociationAlias(string $associationName, string $alias): Criteria
//    {
//        $this->associationAlias[$associationName] = $alias;
//        return $this;
//    }
//
//    public function filter(array|null $filters)
//    {
//        if (!empty($filters)) {
//            $filters = is_string($filters[0]) ? [$filters] : $filters;
//            foreach ($filters as [$field, $op, $value]) {
//                if (!is_null($value)) {
//                    $this->where($field, $op, $value);
//                }
//            }
//        }
//    }
//
//    public function where($column, $operator = null, $value = null, $boolean = 'and'): static
//    {
//        if ($column instanceof Closure) {
//            return parent::where($column, $operator, $value, $boolean);
//        }
//        if (is_array($column)) {
//            return parent::where($column, $operator, $value, $boolean);
//        }
//        if ($value instanceof Criteria) {
//            $this->where($column, $operator,
//                fn($query) => Arr::map(
//                    get_object_vars($query),
//                    fn($_, $property) => $query->$property = $value->$property
//                )
//            );
//        } else {
//            $uOp = strtoupper($operator ?? "");
//            if ($uOp == 'STARTSWITH') {
//                $operator = 'LIKE';
//                $value = $value . '%';
//            } elseif ($uOp == 'CONTAINS') {
//                $operator = 'LIKE';
//                $value = '%' . $value . '%';
//            }
//            $uValue = is_string($value) ? strtoupper($value) : $value;
//            if (($uValue === 'NULL') || is_null($value)) {
//                $this->whereNull($column);
//            } else if ($uValue === 'NOT NULL') {
//                $this->whereNotNull($column);
//            } else if ($uOp === 'IN') {
//                $this->whereIn($column, $value);
//            } else if ($uOp === 'NOT IN') {
//                $this->whereNotIn($column, $value);
//            } else {
//                parent::where($column, $operator, $value, $boolean);
//            }
//        }
//        return $this;
//    }
//
//    public function order(array|string|null $orders)
//    {
//        if (!empty($orders)) {
//            if (is_string($orders)) {
//                $this->orderBy($orders, 'asc');
//            } else {
//                $orders = is_string($orders[0]) ? [$orders] : $orders;
//                foreach ($orders as $spec) {
//                    $this->orderBy($spec[0], $spec[1] ?? 'asc');
//                }
//            }
//        }
//    }
//
//    public function joinClass($className, $alias, $first, $operator = null, $second = null, $type = 'inner', $where = false)
//    {
//        $this->registerClass($className);
//        $tableName = $this->tableName($className);
//        $this->alias($alias, $className);
//        $this->join($tableName . ' as ' . $alias, $first, $operator, $second, $type, $where);
//        return $this;
//    }
//
//    public function alias($alias, string|Criteria $className = '')
//    {
//        if (is_string($className)) {
//            $this->classAlias[$alias] = $className;
//            $this->tableAlias[$alias] = $this->tableName($className);
//        } else if ($className instanceof Criteria) {
//            $this->criteriaAlias[$alias] = $className;
//        }
//        if ($className == '') {
//            $tableName = $this->tableName($className);
//            $this->tableAlias[$alias] = $tableName;
//            $this->aliasTable = $alias;
//            $this->from($tableName, $alias);
//        }
//        return $this;
//    }
//
//    public function aliasTable(): string|null
//    {
//        return $this->aliasTable;
//    }
//
//    public function joinSub($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false)
//    {
//        $this->criteriaAlias[$as] = $query;
//        return parent::joinSub($query, $as, $first, $operator, $second, $type, $where);
//    }
//
//    public function range(int $page, $rows)
//    {
//        $offset = ($page - 1) * $rows;
//        $this->offset($offset)->limit($rows);
//        return $this;
//    }
//
//    public function addParameter(string $name)
//    {
//        $this->parameters[$name] = null;
//    }
//
//    public function parameters(array $parameters)
//    {
//        foreach ($parameters as $p => $v) {
//            $this->setParameter($p, $v);
//        }
//        return $this;
//    }
//
//    public function setParameter(string $name, $value)
//    {
//        $this->parameters[$name] = $value;
//    }
//
//    public function chunkResult(string $fieldKey = '', string $fieldValue = '')
//    {
//        return $this->get()->pluck($fieldValue,$fieldKey)->all();
//    }
//
//    public function getResult()
//    {
//        return $this->get();
//    }
//
//    public function all()
//    {
//        return $this->get()->all();
//    }
//
//    public function treeResult(string $groupBy)
//    {
//        return $this->getResult()
//        ->groupBy($groupBy);
//    }
//
//    public function plainSQL(string $command, array $params = [])
//    {
//        $databaseName ??= \Orkester\Manager::getOptions('db');
//        return $this->getConnection($databaseName)->select($command, $params);
//    }
//
//    public function select($columns = ['*'])
//    {
//        $allColumns = ((is_array($columns) && ($columns[0] == '*')) || ((is_string($columns) && ($columns == '*'))));
//        if ($allColumns) {
//            $attributes = $this->maps[$this->model]->getAttributeMaps();
//            parent::select(array_keys($attributes));
//        } else {
//            parent::select($columns);
//        }
//        return $this;
//    }
}
