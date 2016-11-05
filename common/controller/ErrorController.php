<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 7/9/2016
 * Time: 7:27 PM
 */

namespace common\controller;
use engine\abstracts\ControllerParent;

/**
 * Class ErrorController
 * @package common\controller
 */
class ErrorController extends ControllerParent
{
    /**
     * Page not found error
     * method index
     */
    public function index()
    {
        return "<h2 style='color: brown;'>Page not found</h2>";
    }

    /**
     * @return string
     */
    public static function haveToLogin()
    {
        echo "You have to login to do this action";
        exit(" <a href='".prevURL()."'>".langLine('back')."</a>");
    }
}