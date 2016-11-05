<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 7/27/2016
 * Time: 1:17 AM
 */

namespace common\controller;
use common\controller\master\ReleaseController;


/**
 * Class WelcomeController
 * @package common\controller
 */
class WelcomeController extends ReleaseController
{
    public function index()
    {
        return render("index");
    }
}