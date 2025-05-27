<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('auth_service', 'AuthService');
Flight::register('auth_middleware', "AuthMiddleware");

Flight::route('/*', function () {
    $url = Flight::request()->url;
    error_log("Requested URL: " . $url);
    if (
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0
    ) {
        return TRUE;
    } else {
        try {
            $headers = getallheaders();
            $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;


            // DEBUG: check raw header
            error_log("Authorization Header: " . var_export($authHeader, true));

            // Extract token using regex
            if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                Flight::halt(401, "Missing or malformed Authorization header");
            }

            $token = $matches[1];
            Flight::auth_middleware()->verifyToken($token);
            return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});

require_once __DIR__ . '/services/ProductService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/OrderService.php';
require_once __DIR__ . '/services/CartService.php';
require_once __DIR__ . '/services/OrderItemService.php';
require_once __DIR__ . '/services/WishlistService.php';
require_once __DIR__ . '/services/PaymentService.php';
require_once __DIR__ . '/services/AuthService.php';

Flight::register('productService', 'ProductService');
Flight::register('userService', 'UserService');
Flight::register('orderService', 'OrderService');
Flight::register('cartService', 'CartService');
Flight::register('orderItemService', 'OrderItemService');

require_once __DIR__ . '/routes/ProductRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/OrderRoutes.php';
require_once __DIR__ . '/routes/AuthRoute.php';
require_once __DIR__ . '/routes/CartRoutes.php';
require_once __DIR__ . '/routes/OrderItemRoutes.php';
require_once __DIR__ . '/routes/WhitelistRoute.php';
require_once __DIR__ . '/routes/PaymentRoute.php';



Flight::start();