<?php

namespace Admin\Controller;

use Think\Controller;

class OrderController extends CommonController {

    public function index()
    {
        $this->display('order/order-list');
    }
}