Panada Router
=======================

A simple library to route [Panada Framework](http://panadaframework.com/) 1.*

## Installing PanadaRouter

The recommended way to install Guzzle is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest version of PanadaRouter:

```bash
composer.phar require rakaaditya/panada-router
```

#### First, create AliasController.php in Controllers folder:

```php
namespace Controllers;
use Rakaaditya\PanadaRouter\Routes as Route;

/**
 * This class is for routing alias 
 *
 * @package MyApp
 * @author raka aditya <raka@detik.com>
 * @since version 1.0 <March 2015> 
 */

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
        $route->get('{username}/{id}/{slug}', 'PostController@detail');

        // POST sample
        // http://localhost:8000/post/create/
        $route->post('post/create', 'PostController@store')

        // PUT sample
        // http://localhost:8000/post/edit/
        $route->post('post/edit', 'PostController@update')

        // DELETE sample
        // http://localhost:8000/post/delete/2/
        $route->post('post/delete/{id}', 'PostController@delete')
        
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
