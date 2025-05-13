<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CartDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class CartService extends BaseService {
    private $productDao;

    public function __construct() {
        parent::__construct(new CartDao());
        $this->productDao = new ProductDao();
    }

    public function getCartByUserId($userId) {
        return $this->dao->getByUserId($userId);
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        if ($quantity <= 0) {
            throw new Exception("Quantity must be at least 1.");
        }
        $product = $this->productDao->getById($productId);
        if (!$product) {
            throw new Exception("Product not found.");
        }
        if ($product['Stock'] < $quantity) {
            throw new Exception("Not enough stock available.");
        }
        $existing = $this->dao->getByUserId($userId);
        foreach ($existing as $item) {
            if ($item['ProductID'] == $productId) {
                return $this->dao->update($item['CartID'], [
                    'Quantity' => $item['Quantity'] + $quantity
                ]);
            }
        }
        return $this->dao->insert([
            'UserID' => $userId,
            'ProductID' => $productId,
            'Quantity' => $quantity
        ]);
    }

    public function removeFromCart($userId, $productId) {
        return $this->dao->deleteByUserProduct($userId, $productId);
    }

    public function clearCart($userId) {
        $items = $this->getCartByUserId($userId);
        foreach ($items as $item) {
            $this->dao->delete($item['CartID']);
        }
        return true;
    }
}
?>