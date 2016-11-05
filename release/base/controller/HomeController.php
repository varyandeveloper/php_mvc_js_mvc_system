<?php

namespace release\base\controller;
use common\controller\master\ReleaseController;
use engine\objects\Controller;

/**
 * Class UserController
 * @package release\base\controller
 */
class HomeController extends ReleaseController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_setData([
            'title'=>"Welcome",
            'message'=>"Welcome to Var Yan`s MVC system",
            'lang'=>'en'
        ]);
    }

    /**
     * @return \engine\interfaces\ViewSchema
     */
    public function index()
    {
        return view()
            ->withVars($this->_getData())
            ->render('index');
    }
}