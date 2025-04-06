<?php
require_once 'UserDao.php';
require_once 'ProductDao.php';
require_once 'CartDao.php';

// Initialize all DAOs
$userDao = new UserDao();
$productDao = new ProductDao();
$cartDao = new CartDao();

try {
    // Test User operations
    echo "\n=== Testing User Operations ===\n";
    $userEmail = 'testuser@example.com';

    // Check if user exists first
    $userByEmail = $userDao->getByEmail($userEmail);
    if ($userByEmail) {
        echo "User with email $userEmail already exists\n";
    } else {
        $userId = $userDao->insert([
            'name' => 'Test User',
            'email' => $userEmail,
            'password' => password_hash('password123', PASSWORD_DEFAULT)
        ]);
        echo "Inserted new user with ID: $userId\n";
    }

    $users = $userDao->getAll();
    echo "All users: " . print_r($users, true) . "\n";

    $userByEmail = $userDao->getByEmail($userEmail);
    echo "User by email: " . print_r($userByEmail, true) . "\n";

    // Test Product operations
    echo "\n=== Testing Product Operations ===\n";
    $productId = $productDao->insert([
        'Name' => 'Gaming Mouse',
        'Price' => 49.99,
        'Stock' => 100,
        'OnSale' => true,
        'CategoryID' => 1,
        'Description' => 'High DPI RGB Mouse',
        'ImageURL' => 'mouse.jpg'
    ]);
    echo "Inserted product with ID: $productId\n";

    $products = $productDao->getAll();
    echo "All products: " . print_r($products, true) . "\n";

    $productUpdateData = [
        'Name' => 'Updated Gaming Mouse',
        'Price' => 59.99,
        'Stock' => 150,
        'OnSale' => false,
        'Description' => 'Updated description for the mouse',
        'ImageURL' => 'updated_mouse.jpg'
    ];
    $productDao->update($productId, $productUpdateData);
    echo "Updated product\n";

    $product = $productDao->getById($productId);
    echo "Updated product details: " . print_r($product, true) . "\n";

    // Test Cart operations
    echo "\n=== Testing Cart Operations ===\n";
    $cartItemId = $cartDao->insert([
        'UserID' => $userId,
        'ProductID' => $productId,
        'Quantity' => 2
    ]);
    echo "Inserted cart item with ID: $cartItemId\n";

    $cartItems = $cartDao->getByUserId($userId);
    echo "Cart items for user $userId: " . print_r($cartItems, true) . "\n";

    // Test deletion operations
    echo "\n=== Testing Delete Operations ===\n";
    $cartDao->deleteByUserProduct($userId, $productId);
    echo "Deleted cart item\n";

    $userDao->delete($userId);
    echo "Deleted user\n";

    echo "\nAll tests completed successfully!\n";

} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage() . "\n";
}
?>
