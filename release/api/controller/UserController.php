<?php

namespace release\api\controller;
use common\controller\master\ReleaseController;
use engine\traits\Output;

/**
 * Class UserController
 * @package release\base\controller
 */
class UserController extends ReleaseController
{
    use Output;

    /**
     * @return string
     */
    public function index()
    {
        return $this->json([]);
    }
}