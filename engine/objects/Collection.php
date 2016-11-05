<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 11-Aug-16
 * Time: 13:49
 */

namespace engine\objects;

/**
 * Class Collection
 * @package engine\objects
 */
class Collection implements \Iterator
{
    /**
     * @var int $position
     */
    protected $position = 0;
    /**
     * @var \PDOStatement $_state
     */
    protected static $_state;
    /**
     * @var bool $_asObject
     */
    protected static $_asObject = true;
    /**
     * @var array $array
     */
    protected $_items;

    /**
     * Collection constructor.
     * @param \PDOStatement $statement
     */
    public function __construct($statement)
    {
        if($statement instanceof \PDOStatement)
            self::$_state = $statement;
        elseif (is_array($statement))
            $this->_items = $statement;
        elseif(is_object($statement))
            $this->_items = (array)$this->_items;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        if(!is_null(self::$_state))
        {
            $this->_items = self::$_asObject
                ? self::$_state->fetchAll(\PDO::FETCH_OBJ)
                : self::$_state->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $this->_items;
    }

    /**
     * @return $this
     */
    public function asArray()
    {
        self::$_asObject = false;
        return $this;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [
            "items"=>$this->getItems()
        ];
    }

    /**
     * @return mixed|null
     */
    public function current()
    {
        $item = $this->valid()
            ? $this->_items[$this->position]
            : null;
        return $item;
    }

    /**
     * @return $this
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if(self::$_state && sizeof($this->_items) == 0)
        {
            $this->_items = self::$_asObject
                ? self::$_state->fetchAll(\PDO::FETCH_OBJ)
                : self::$_state->fetchAll(\PDO::FETCH_ASSOC);
        }
        return !empty($this->_items[$this->position]);
    }

    /**
     * @return $this
     */
    public function rewind()
    {
        $this->position = 0;
    }

}