<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
//
// https://glenneggleton.com/page/psr-7-permissions
// https://www.sitepoint.com/role-based-access-control-in-php/
// http://blog.programster.org/slim3-use-middleware-to-check-user-is-logged-in

// use Slim\Http\Request;
// use Slim\Http\Response;
// use Slim\Container;


// Check the user is logged in when necessary.
// $loggedInMiddleware = function (Request $request, Response $response, $next) {
//     $route = $request->getAttribute('route');

//     if ( $route == null ) {
//         redirect( getBaseURL().'adm/login' );
//         exit();
//     }

//     $routeName = $route->getName();
//     $groups = $route->getGroups();
//     $methods = $route->getMethods();
//     $arguments = $route->getArguments();


//     # Define routes that user does not have to be logged in with. All other routes, the user
//     # needs to be logged in with.
//     $publicRoutesArray = array(
//         'site',
//         'root',
//         'login',
//         'logout',
//         'register',
//         'forgot-password'
//     );

//     if ( ! isset($_SESSION['user']) && ! in_array($routeName, $publicRoutesArray) ) {
//         // redirect the user to the login page and do not proceed.
//         $response = $response->withRedirect('/adm/login');

//     } else {
//         // Proceed as normal...
//         $response = $next($request, $response);
//     }

//     return $response;
// };


// Apply the middleware to every request.
// $app->add($loggedInMiddleware);
// $app->add($container['AuthMiddleware']);

