<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 8/8/2016
 * Time: 8:54 PM
 */

namespace engine\objects\model;

use engine\interfaces\DatabaseSchema;
use engine\objects\Factory;
use engine\traits\Instance;

/**
 * Class State
 * @package engine\objects\model
 */
class State
{
    use Instance;

    /**
     * @var DatabaseSchema $_state
     */
    protected $_state;
    /**
     * @var DatabaseSchema $_db
     */
    protected $_db;

    /**
     * State constructor.
     */
    public function __construct()
    {
        $this->_db = Factory::create(\engine\objects\Database::class);
        $this->_state = $this->_db;
    }

    /**
     * @param $dbFunc
     * @param array ...$args
     * @return $this
     */
    public function attach($dbFunc, ...$args)
    {
        $this->_state = sizeof($args)
            ? call_user_func_array([$this->_state, $dbFunc], $args)
            : call_user_func([$this->_state, $dbFunc]);

        return $this;
    }

    /**
     * @param bool $withQuery
     * @return \PDOStatement
     */
    public function state($withQuery = true)
    {
        return $withQuery
            ? $this->_state->query()
            : $this->_state;
    }
}