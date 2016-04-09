<?php

require_once 'Dummy/DummyMiddleware.php';
require_once 'Dummy/DummyController.php';

class GroupTest extends PHPUnit_Framework_TestCase  {

    protected $result;

    public function __construct() {
        // Initial setup
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/api/v1/test';
        $_SERVER['REQUEST_METHOD'] = 'get';
    }

    protected function group() {
        $this->result = true;
    }

    public function testGroup() {
        \Pecee\SimpleRouter\RouterBase::reset();

        $this->result = false;

        \Pecee\SimpleRouter\SimpleRouter::group(['prefix' => '/group'], $this->group());

        try {
            \Pecee\SimpleRouter\SimpleRouter::start();
        } catch(Exception $e) {

        }

        $this->assertTrue($this->result);
    }

    public function testNestedGroup() {
        \Pecee\SimpleRouter\RouterBase::reset();

        \Pecee\Http\Request::getInstance()->setUri('/api/v1/test');

        \Pecee\SimpleRouter\SimpleRouter::group(['prefix' => '/api'], function() {
            \Pecee\SimpleRouter\SimpleRouter::group(['prefix' => '/v1'], function() {
                \Pecee\SimpleRouter\SimpleRouter::get('/test', 'DummyController@start');
            });
        });

        \Pecee\SimpleRouter\SimpleRouter::start();
    }

}