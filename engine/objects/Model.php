<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 12:12
 */

namespace engine\objects;

use engine\abstracts\ModelParent;
use engine\interfaces\DynamicFormModelSchema;

/**
 * Class model
 * @package engine\objects
 */
class Model extends ModelParent implements DynamicFormModelSchema
{
    /**
     * @var array $_connections
     */
    protected $_connections = [];
    /**
     * @var array $_creatable
     */
    protected $_creatable = [];
    /**
     * @var array $_hidden
     */
    protected $_hidden = [];

    /**
     * @return array
     */
    public function getCreatable()
    {
        return $this->_creatable;
    }

    /**
     * @return array
     */
    public function getHidden()
    {
        return $this->_hidden;
    }

    /**
     * @return array
     */
    public function getConnections()
    {
        return $this->_connections;
    }
}