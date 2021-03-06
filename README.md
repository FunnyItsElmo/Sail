# Sail [Discontinued]

[![Latest Stable Version](https://poser.pugx.org/funnyitselmo/sail/v/stable)](https://packagist.org/packages/funnyitselmo/sail) [![Total Downloads](https://poser.pugx.org/funnyitselmo/sail/downloads)](https://packagist.org/packages/funnyitselmo/sail) [![Latest Unstable Version](https://poser.pugx.org/funnyitselmo/sail/v/unstable)](https://packagist.org/packages/funnyitselmo/sail) [![License](https://poser.pugx.org/funnyitselmo/sail/license)](https://packagist.org/packages/funnyitselmo/sail)

Sail is a lightweight object-oriented micro-framework written in PHP to create quick and simple yet powerful APIs.
### Installation
```
composer require funnyitselmo/sail
```
### Hello World
```PHP
use Sail\Sail;
use Sail\Tree;
use Sail\Middleware;
use Sail\Exceptions\NoSuchRouteException;
use Sail\Exceptions\NoMiddlewareException;
use Sail\Exceptions\NoCallableException;

require '../vendor/autoload.php';

/**
 * The very first step is to create the Sail object
 */
$sail = new Sail();

/**
 * This will display 'Hello World!' if you send a simple GET request
 * GET $sail->get()
 * POST $sail->post()
 * PUT $sail->put()
 * PATCH $sail->patch()
 * DELETE $sail->delete()
 * OPTIONS $sail->options()
 */
$sail->get('/', function  ($request, $response) {
        $response->setData('Hello World!');
});

/**
 * Lets create a new sub tree which will handle all request that start
 * with test or any other route we will specifiy later
 * NOTE: Sail also extends Tree
 */
class TestTree extends Tree {

        public function build () {
        
                $this->get('/', function  ($request, $response) {
                        $response->setData('You can see me if you request /test');
                });
                
                //everything that is in curly braces is a variable
                $this->get('/{id}', function  ($request, $response, $id) {
                        $data = array('I can also handle variables! request /test/42', '{id} is ' . $id);
                    
                        $response->setHeaders(array(
                                'Content-Type' => 'application/json'
                         ));
                    
                        $response->setData(json_encode($data));
                });
                
        }
        
}

/**
 * It is time to create our first middleare.
 */
class AuthMiddleware implements Middleware {

        public function call() {
                //check if the user is allowed to view the route
                return true;
        }

}

/**
 * The following code part adds the route test to the router.
 * The Route links to a tree and just passes the request if the
 * middleware, in this case the AuthMiddleware, allows the request.
 */
$sail->tree('/test', new AuthMiddleware(), new TestTree());

/**
 * Now we just need to run the code and catch the 
 * perhaps occurring exceptions
 */
try {
        $sail->run();
} catch (NoSuchRouteException $e) {
        echo $e->getMessage();
} catch (NoMiddlewareException $e) {
        echo $e->getMessage();
} catch (NoCallableException $e) {
        echo $e->getMessage();
}
```
