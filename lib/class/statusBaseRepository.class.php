<?php

/**
 * Class statusBaseRepository
 */
class statusBaseRepository
{
    const DEFAULT_LIMIT = 30;
    const DEFAULT_OFFSET = 0;

    protected $entity;

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     *
     * @return statusBaseRepository
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return static
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return static
     */
    public function resetLimitAndOffset()
    {
        $this->limit = 0;
        $this->offset = 0;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return static
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }


    /**
     * @return waModel
     * @throws waException
     */
    public function getModel()
    {
        return stts()->getModel($this->getEntity());
    }

    /**
     * @param $id
     *
     * @return array|mixed
     * @throws waException
     */
    public function findById($id)
    {
        if (!is_array($id)) {
            $cached = $this->getFromCache($id);
            if ($cached) {
                return $cached;
            }
        }

        $data = $this->getModel()->getById($id);
        if (!$data) {
            return null;
        }

        $all = false;

        if (is_array($id) && !is_array($this->getModel()->getTableId())) {
            $all = true;
        }

        $entities = $this->generateWithData($data, $all);

        if (!$all && $entities) {
            $this->cache($id, $entities);
        }

        return $entities;
    }

    /**
     * @param      $field
     * @param null $value
     * @param bool $all
     * @param bool $limit
     *
     * @return statusAbstractEntity[]|statusAbstractEntity
     * @throws waException
     */
    public function findByFields($field, $value = null, $all = false, $limit = false)
    {
        if (is_array($field)) {
            $data = $this->getModel()->getByField($field, $all, $limit);
        } else {
            $data = $this->getModel()->getByField($field, $value, $all, $limit);
        }

        $objs = $this->generateWithData($data, $all);

        return $all ? ($objs ?: []) : $objs;
    }

    /**
     * @return statusAbstractEntity[]|statusAbstractEntity
     * @throws waException
     */
    public function findAll()
    {
        $data = $this->getModel()->getAll();

        return $this->generateWithData($data, true);
    }

    /**
     * @param array $data
     * @param bool  $all
     *
     * @return array
     */
    public function generateWithData($data, $all = false)
    {
        if (empty($data)) {
            return [];
        }

        if ($all === false) {
            $data = [$data];
        }

        $objects = [];

        $factory = stts()->getEntityFactory($this->entity);
        foreach ($data as $datum) {
            $objects[] = $factory->createNewWithData($datum);
        }

        return !$all ? reset($objects) : $objects;
    }

    /**
     * @return array
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param array $cache
     *
     * @return statusBaseRepository
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param $key
     *
     * @return bool|statusAbstractEntity
     */
    protected function getFromCache($key)
    {
        if (isset($this->cache[$this->entity][$key])) {
            return $this->cache[$this->entity][$key];
        }

        return false;
    }

    /**
     * @param $key
     * @param $entity
     */
    protected function cache($key, $entity)
    {
        if (!isset($this->cache[$this->entity])) {
            $this->cache[$this->entity] = [];
        }

        $this->cache[$this->entity][$key] = $entity;
    }
}
