<?php
require_once 'UserDao.php';
require_once 'ProductDao.php';
require_once 'CartDao.php';

// Initialize all DAOs
$userDao = new UserDao();
$productDao = new ProductDao();
$cartDao = new CartDao();

try {
    // Clean up previous test data
    echo "\n=== Cleaning Up Previous Test Data ===\n";
    $testUsers = $userDao->getAll();
    foreach ($testUsers as $user) {
        if (in_array($user['Email'], ['admin@example.com', 'user@example.com'])) {
            $userDao->delete($user['UserID']);
            echo "Deleted previous test user: {$user['Email']}\n";
        }
    }

    // Test User operations
    echo "\n=== Testing User Operations ===\n";
    
    // Create admin user
    $adminId = $userDao->insert([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'address' => 'Admin Address',
        'roleId' => 1  // Assuming 1 is admin role
    ]);
    echo "Created admin user with ID: $adminId\n";

    // Create normal user
    $userId = $userDao->insert([
        'name' => 'Normal User',
        'email' => 'user@example.com',
        'password' => password_hash('user123', PASSWORD_DEFAULT),
        'address' => 'User Address',
        'roleId' => 2  // Assuming 2 is normal user role
    ]);
    echo "Created normal user with ID: $userId\n";

    // List all users
    $users = $userDao->getAll();
    echo "All users: " . print_r($users, true) . "\n";

    // Test Product operations
    echo "\n=== Testing Product Operations ===\n";
    $productsToInsert = [
        [
            'Name' => 'FC25 Playstation5',
            'Price' => 80.00,
            'Stock' => 50,
            'OnSale' => 0,
            'CategoryID' => 1,
            'Description' => 'FIFA 25 for Playstation 5',
            'ImageURL' => 'frontend/assets/fifa.jpg'
        ],
        [
            'Name' => 'XBox X Series',
            'Price' => 1000.00,
            'Stock' => 30,
            'OnSale' => 1,
            'CategoryID' => 1,
            'Description' => 'Microsoft Xbox X Series gaming console',
            'ImageURL' => 'frontend/assets/xseries.jpg'
        ],
        [
            'Name' => 'Apple Watch Series 7',
            'Price' => 25.00,
            'Stock' => 100,
            'OnSale' => 1,
            'CategoryID' => 2,
            'Description' => 'Apple Watch Series 7 smartwatch',
            'ImageURL' => 'frontend/assets/apple-watch-series-7-edst-41mm-1643016727.jpg'
        ],
        [
            'Name' => 'Razer Black Widow Keyboard',
            'Price' => 40.00,
            'Stock' => 75,
            'OnSale' => 0,
            'CategoryID' => 3,
            'Description' => 'Razer Black Widow mechanical gaming keyboard',
            'ImageURL' => 'frontend/assets/razertest.jpg'
        ],
        [
            'Name' => 'Logitech Gaming Mouse',
            'Price' => 25.00,
            'Stock' => 120,
            'OnSale' => 1,
            'CategoryID' => 3,
            'Description' => 'Logitech gaming mouse with high DPI',
            'ImageURL' => 'frontend/assets/mouse.jpg'
        ],
        [
            'Name' => 'Logitech Speakers',
            'Price' => 280.00,
            'Stock' => 40,
            'OnSale' => 0,
            'CategoryID' => 4,
            'Description' => 'Logitech Z150 multimedia stereo speakers',
            'ImageURL' => 'frontend/assets/logitech-z150-multimedia-stereo-speakers-1-1.jpg'
        ],
        [
            'Name' => 'Logitech G29',
            'Price' => 18.00,
            'Stock' => 25,
            'OnSale' => 1,
            'CategoryID' => 3,
            'Description' => 'Logitech G29 racing wheel',
            'ImageURL' => 'frontend/assets/g29.jpg'
        ],
        [
            'Name' => 'Playstation 5',
            'Price' => 40.00,
            'Stock' => 60,
            'OnSale' => 0,
            'CategoryID' => 1,
            'Description' => 'Sony Playstation 5 gaming console',
            'ImageURL' => 'frontend/assets/G03.jpg'
        ]
    ];

    // Insert all products
    foreach ($productsToInsert as $product) {
        $productId = $productDao->insert($product);
        echo "Inserted product: {$product['Name']} with ID: $productId\n";
    }

    // List all products
    $products = $productDao->getAll();
    echo "All products: " . print_r($products, true) . "\n";

    // Test Cart operations
    echo "\n=== Testing Cart Operations ===\n";
    // Add some products to user's cart
    $cartItem1 = $cartDao->insert([
        'UserID' => $userId,
        'ProductID' => 1, // FC25 Playstation5
        'Quantity' => 2
    ]);
    echo "Added FC25 Playstation5 to cart\n";

    $cartItem2 = $cartDao->insert([
        'UserID' => $userId,
        'ProductID' => 3, // Apple Watch
        'Quantity' => 1
    ]);
    echo "Added Apple Watch to cart\n";

    // View cart
    $cartItems = $cartDao->getByUserId($userId);
    echo "Cart items for user $userId: " . print_r($cartItems, true) . "\n";

    echo "\n=== Testing Complete ===\n";
    echo "Successfully:\n";
    echo "- Created admin and normal users\n";
    echo "- Inserted all 8 products\n";
    echo "- Tested cart operations\n";

} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage() . "\n";
}
?>