<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/CartDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class OrderService extends BaseService {
    private $orderItemDao;
    private $cartDao;
    private $productDao;

    public function __construct() {
        parent::__construct(new OrderDao());
        $this->orderItemDao = new OrderItemDao();
        $this->cartDao = new CartDao();
        $this->productDao = new ProductDao();
    }

    public function createOrderFromCart($userId, $shippingAddress) {
        if (empty($shippingAddress)) {
            throw new Exception("Shipping address is required.");
        }
        $cartItems = $this->cartDao->getByUserId($userId);
        if (empty($cartItems)) {
            throw new Exception("Cart is empty.");
        }
        $totalAmount = 0;
        $orderItems = [];
        foreach ($cartItems as $item) {
            $product = $this->productDao->getById($item['ProductID']);
            if ($product['Stock'] < $item['Quantity']) {
                throw new Exception("Not enough stock for product: " . $product['Name']);
            }
            $itemTotal = $product['Price'] * $item['Quantity'];
            $totalAmount += $itemTotal;
            $orderItems[] = [
                'ProductID' => $product['ProductID'],
                'Quantity' => $item['Quantity'],
                'Price' => $product['Price']
            ];
        }
        $orderId = $this->dao->insert([
            'UserID' => $userId,
            'TotalAmount' => $totalAmount,
            'ShippingAddress' => $shippingAddress
        ]);
        foreach ($orderItems as $item) {
            $this->orderItemDao->insert(array_merge(['OrderID' => $orderId], $item));
            $this->productDao->updateStock($item['ProductID'], 
                $product['Stock'] - $item['Quantity']);
        }
        $this->cartDao->clearCart($userId);
        return $orderId;
    }

    public function getOrdersByUser($userId) {
        return $this->dao->getByUserId($userId);
    }
}
?>