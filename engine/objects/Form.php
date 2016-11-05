<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 8:34 PM
 */

namespace engine\objects;

use engine\abstracts\FormParent;
use engine\traits\form\DynamicModel;

/**
 * Class Form
 * @package engine\objects
 */
class Form extends FormParent
{
    use DynamicModel;
}