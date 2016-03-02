# PanadaRouter
[![Build Status](https://travis-ci.org/rakaaditya/panada-router.svg?branch=master)](https://travis-ci.org/rakaaditya/panada-router)

A simple library to route [Panada Framework](http://panadaframework.com/) 1.*

```php
$route->get('/home', 'HomeController@index');
$route->get('{username}/{id}/{slug}', 'ArticleController@detail');
$route->post('post/create', 'ArticleController@store');

$route->run();
```

## Installation

The recommended way to install PanadaRouter is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest version of PanadaRouter:

```bash
composer.phar require rakaaditya/panada-router
```
## Configuration

#### First, create AliasController.php in Controllers folder:

```php
namespace Controllers;
use Rakaaditya\PanadaRouter\Routes as Route;

class AliasController
{
    public function index()
    {
        $route = new Route;
        
        // GET sample
        // http://localhost:8000/home/
        $route->get('coba', 'HomeController@index');

        // GET using parameters sample
        // http://localhost:8000/raka/10/post-title/
        $route->get('{username}/{id}/{slug}', 'ArticleController@detail');

        // POST sample
        // http://localhost:8000/post/create/
        $route->post('post/create', 'ArticleController@store')

        // PUT sample
        // http://localhost:8000/post/edit/
        $route->put('post/edit', 'ArticleController@update')

        // DELETE sample
        // http://localhost:8000/post/delete/2/
        $route->delete('post/delete/{id}', 'ArticleController@delete')
        
        // Let's run through the route!!
        $route->run();
    }
}

```

#### Then add the following configuration in main config:
```php
'alias' => [
    'controller' => [
        'class' => 'AliasController',
        'method' => 'index'
    ],
 ],
```
