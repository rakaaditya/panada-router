<?php

use Rakaaditya\PanadaRouter\Routes as Route;

class RoutesTest extends \PHPUnit_Framework_TestCase  
{
    public function testStaticGet()
    {
        $route = new Route;

        $route->get('home', 'HomeController@index');

        $parsed = $route->parse('home', 'GET');

        $this->assertEquals('\Controllers\HomeController', $parsed['controller']);
        $this->assertEquals('index', $parsed['method']);
    }    

    public function testPatternGet()
    {
        $route = new Route;

        $route->get('detail/{id}/{slug}', 'ArticleController@detail');

        $parsed = $route->parse('detail/1/this-is-a-slug', 'GET');

        $this->assertEquals('\Controllers\ArticleController', $parsed['controller']);
        $this->assertEquals('detail', $parsed['method']);
        $this->assertEquals(['id' => '1', 'slug' => 'this-is-a-slug'], $parsed['args']);
    }

    public function testPost()
    {
        $route = new Route;

        $route->post('article/create', 'ArticleController@store');

        $parsed = $route->parse('article/create', 'POST');

        $this->assertEquals('\Controllers\ArticleController', $parsed['controller']);
        $this->assertEquals('store', $parsed['method']);
    }

    public function testPut()
    {
        $route = new Route;

        $route->put('article/edit/{id}', 'ArticleController@update');

        $parsed = $route->parse('article/edit/1', 'PUT');

        $this->assertEquals('\Controllers\ArticleController', $parsed['controller']);
        $this->assertEquals('update', $parsed['method']);
    }

    public function testDelete()
    {
        $route = new Route;

        $route->delete('article/delete/{id}', 'ArticleController@delete');

        $parsed = $route->parse('article/delete/1', 'DELETE');

        $this->assertEquals('\Controllers\ArticleController', $parsed['controller']);
        $this->assertEquals('delete', $parsed['method']);
    }
}