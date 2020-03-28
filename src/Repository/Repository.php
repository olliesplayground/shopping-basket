<?php


namespace App\Repository;


use App\Services\Database\DatabaseInterface;

class Repository
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var DatabaseInterface
     */
    protected $database;

    public function __construct(string $table, string $entity, DatabaseInterface $database)
    {
        $this->table = $table;
        $this->entity = $entity;
        $this->database = $database;
    }

    /**
     * @return array
     */
    public function get(): ?array
    {
        $all = $this->database->select('*')
            ->from($this->table)
            ->get();

        return array_map([$this, 'translate'], $all);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOne($id)
    {
        $result = $this->database->select('*')
            ->from($this->table)
            ->where( [ $this->primaryKey => $id ] )
            ->get();

        if (empty($result)) {
            return null;
        }

        return $this->translate($result[0]);
    }

    /**
     * @param $object
     * @return mixed
     */
    protected function translate($object)
    {
        return new $this->entity( ...array_values( $this->convertToArray($object) ) );
    }

    /**
     * @param $object
     * @return array
     */
    protected function convertToArray($object): array
    {
        return is_object($object) ? (array) $object : $object;
    }
}
