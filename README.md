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

### First, add the following configuration at app/config/main.php file:
```php
'alias' => [
    'controller' => [
        'class' => 'AliasController',
        'method' => 'index'
    ],
 ],
```

### Then, create AliasController.php in Controllers folder:

```php
namespace Controllers;
use Rakaaditya\PanadaRouter\Routes as Route;

class AliasController
{
    public function index()
    {
        $route = new Route;
        $route->get('coba', 'HomeController@index');
        $route->get('{username}/{id}/{slug}', 'ArticleController@detail');

        // Let's run through the route!!
        $route->run();
    }
}

```
### Basic Usage
```php
// GET
$route->get('posts', 'PostController@posts');
// POST
$route->post('posts/create', 'PostController@create');
// PUT
$route->put('posts/{id}/update', 'PostController@update');
// DELETE
$route->delete('posts/{id}/delete', 'PostController@delete');
```

### Grouping with prefix
```php
$route->group('posts', function($route) {
    $route->get('/', 'PostController@posts');
    $route->post('create', 'PostController@create');
    $route->put('{id}/update', 'PostController@update');
    $route->delete('{id}/delete', 'PostController@delete');
});
```
