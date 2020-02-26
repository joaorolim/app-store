<?php

namespace BET\Auth;

use Slim\Container;

class AuthMiddleware
{
	/**
     * @var Container
     */
    protected $c;


    public function __construct( Container $c ) {
        $this->c = $c;
    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke( $request, $response, $next )
    {
        // $this->container has the DI
        $route = $request->getAttribute('route');

        if ( $route == null ) {
            redirect( getBaseURL().'adm/login' );
            exit();
        }

        $routeName = $route->getName();
        $groups = $route->getGroups();
        $methods = $route->getMethods();
        $arguments = $route->getArguments();


        # Define routes that user does not have to be logged in with. All other routes, the user
        # needs to be logged in with.
        $publicRoutesArray = array(
            'site',
            'root',
            'login',
            'logout',
            'register',
            'forgot-password'
        );

        if ( ! isset($_SESSION['user']) && ! in_array($routeName, $publicRoutesArray) ) {
            // redirect the user to the login page and do not proceed.
            $response = $response->withRedirect('/adm/login');

        } else {

                $user = $_SESSION['user'];

                $objUser = $this->c->User;
                $objUser->setEmail( $user['email'] );

                $objServiceUser = $this->c->ServiceUser;
                $objServiceUser = $objServiceUser->getUserByEmail();

                if ( ! ($objServiceUser) ) {
                    $_SESSION['user'] = null;
                    unset($_SESSION['user']);
                    return $response->withRedirect('/adm/login');
                }

                $idRole = $user['role'];

                $objServicePermission = $this->c->ServicePermission;
                $result = $objServicePermission->getPermissionsByRole( $idRole );

                $perms = $result[0]['permissions'];

                $permissions = array( 'home','perfil','perfil-alt' );
                $permissions = array_merge( $permissions, $publicRoutesArray );

                foreach ($perms as $arr => $perm) {
                    if ( $perm['checked'] === "checked" ) {
                        $permissions[] = $perm['permRoute'];
                    }
                }

                $_SESSION['routeName'] = $routeName;
                $_SESSION['permissions'] = $permissions;

                if ( in_array($routeName, $permissions) ) {
                    // tem permissão para acessar essa rota
                    $response = $next($request, $response);
                } else {
                    setMessage('Você não tem permissão para acessar essa página!', 'danger');
                    // Must return a $response object
                    $response = $response->withRedirect('/adm/home');
                }

        }

        return $response;

    }
}
