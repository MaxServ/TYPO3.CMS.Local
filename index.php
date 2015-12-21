<?php
define('REVIEW_DOCUMENT_ROOT', __DIR__);

require_once __DIR__ . '/vendor/autoload.php';

use MaxServ\Typo3Local\SiteController;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$locator = new FileLocator(array(REVIEW_DOCUMENT_ROOT . '/Configuration'));
$loader = new YamlFileLoader($locator);
$routes = $loader->load('Routes.yml');

$request = Request::createFromGlobals();

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher));

$resolver = new ControllerResolver();
$kernel = new HttpKernel($dispatcher, $resolver);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);

//if (isset($params['_controller'])) {
//    call_user_func_array(array($params['_controller'], $params['action']), array($params));
//}
//$availableApiRoutes = [];
//foreach ($routeCollection as $name => $route) {
//    $route = $route->compile();
//    if( strpos($name, "api_") !== 0 ){
//        $emptyVars = [];
//        foreach( $route->getVariables() as $v ){
//            $emptyVars[ $v ] = $v;
//        }
//        $url = $this->generateUrl( $name, $emptyVars );
//        $availableApiRoutes[] = ["name" => $name, "url" => $url, "variables" => $route->getVariables()];
//    }
//}
//
//$dump = $availableApiRoutes; echo '<xmp style="position: relative; z-index: 1000; background: #fefefe; border: 1px solid #ccc; padding: 6px; margin: 6px; box-shadow: 6px 6px 4px #888; border-radius: 4px;">' . var_export ($dump, TRUE) . '</xmp>'; die();
//
