<?php

namespace project\Models;

use project\Services\DB;

abstract class ActiveRecordEntity
{
    protected $id;

    public function getId() : int
    {
        return $this->id;
    }

    public function __set($name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source) : string
    {
        return lcfirst(str_replace('_','',ucwords($source,'_')));
    }

    public static function findAll() : array
    {
        $db = DB::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;',
            [],
            static::class
        );
    }

    public static function findAllComments(int $articleId) : array
    {
        $db = DB::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . "` WHERE article_id=$articleId;",
            [],
        static::class);
    }

    abstract protected static function getTableName(): string;

    public static function getById(int $id) : ?self
    {
        $db = DB::getInstance();
        $entities = $db->query('SELECT * FROM`' . static::getTableName() . "` WHERE id=$id;",
            [],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    private function camelCaseToUnderscore(string $source) : string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
        // (?<!^)[A-Z] - берем большие буквы, при этом самую первую букву в начале строки не трогаем
        // _$0 - это знак подчеркивания, за которым следует нулевое совпадение в регулярке
        // (нулевое - это вся строка, попавшая под регулярку.
        // В нашем случае - это одна большая буква).
        // Таким образом, с помощью preg_replace, мы заменяем все большие буквы
        // A - Z на _A - _Z. А затем с помощью strtolower приводим всю строку к нижнему регистру.
    }

    public function save() : void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
        //var_dump($mappedProperties);
    }

    private function mapPropertiesToDbFormat() : array {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();
        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    private function update(array $mappedProperties) : void
    {
        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index;
            $columns2params[] = $column . ' = ' . $param;
            $params2values[$param] = $value;
            $index++;
        }

        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
        $db = DB::getInstance();
        $db->query($sql, $params2values, static::class);

    }

    private function insert(array $mappedProperties) : void
    {
        /*$columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index;
            $columns2params[] = $column . ' = ' . $param;
            $params2values[$param] = $value;
            $index++;
        }

        $sql = 'INSERT INTO ' . static::getTableName() . ' SET ' . implode(', ', $columns2params);
        echo $sql;*/

        $filteredProperties = array_filter($mappedProperties);
        // INSERT INTO `articles` (`author_id`, `name`, `text`) VALUES (:author_id, :name, :text)
        $columns = [];
        $params2values = [];
        $paramNames = [];
        foreach ($filteredProperties as $colName => $value) {
            $columns[] = $colName;
            $param = ':' . $colName;
            $paramNames[] = $param;
            $params2values[$param] = $value;
        }
        $columnsViaDb = implode(', ', $columns);
        $paramNamesViaDb = implode(', ', $paramNames);
        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . $columnsViaDb . ') VALUES (' . $paramNamesViaDb . ' );';
        //echo $sql;

        $db = DB::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();

        //$date = $this::getById($this->id);
        // $date = ($this::getById($this->id))->getCreatedAt(); // выведет дату
        //var_dump($date);
        //$this->setCreatedAt($date->getCreatedAt());
        $this->refresh();
    }

    private function refresh() : void
    {
        $objectFromDb = static::getById($this->id);
        //var_dump($this);
        $reflector = new \ReflectionObject($objectFromDb);
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $this->$propertyName = $property->getValue($objectFromDb);
            //$this->$property = $value;
        }
        //var_dump($this);
    }

    public function delete() : void
    {
        $db = DB::getInstance();
        $sql = 'DELETE FROM ' . static::getTableName() . ' WHERE id = ' . $this->id . ';';
        $db->query($sql, [], static::class);

    }

    public static function findOneByColumn(string $columnName, $value) : ?self
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM ' . static::getTableName() . ' WHERE ' . $columnName . "= '$value' LIMIT 1;",
        [],
        static::class);

        if ($result === []) {
            return null;
        }

        return $result[0];
    }
}
