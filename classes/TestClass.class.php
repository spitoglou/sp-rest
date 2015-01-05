<?php

class TestClass {
    public $var1='Test1';
    public $var2='Δοκιμή 2';
    public $var_id;
    private $var3;
    protected $var4;

    public function __construct($id='') {
        $this->var_id=$id;
    }

    public function testFunc($arg) {
        return 'Test '.$arg;
    }

    public function testFunc2($arg1,$arg2) {
        return 'arg1='.$arg1.' , arg2='.$arg2;
    }


}