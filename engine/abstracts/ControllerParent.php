<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 15:35
 */

namespace engine\abstracts;
use engine\traits\Instance;

/**
 * Class ControllerParent
 * @package engine\abstracts
 */
abstract class ControllerParent
{
    use Instance;
    /**
     * ControllerParent constructor.
     */
    public function __construct()
    {

    }
}