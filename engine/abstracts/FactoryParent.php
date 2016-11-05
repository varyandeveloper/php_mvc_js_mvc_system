<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 14:41
 */

namespace engine\abstracts;

use engine\interfaces\FactorySchema;
use engine\traits\Factory;

/**
 * Class FactoryParent
 * @package engine\abstracts
 */
abstract class FactoryParent implements FactorySchema
{
    use Factory;
}