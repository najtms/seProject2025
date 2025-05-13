<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService extends BaseService {
    public function __construct() {
        parent::__construct(new ProductDao());
    }

    public function addProduct($productData) {
        if ($productData['Price'] <= 0) {
            throw new Exception("Product price must be positive.");
        }
        if ($productData['Stock'] < 0) {
            throw new Exception("Product stock cannot be negative.");
        }
        $required = ['Name', 'Price', 'CategoryID'];
        foreach ($required as $field) {
            if (empty($productData[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
        return $this->dao->insert($productData);
    }

    public function getProductsOnSale() {
        return $this->dao->getOnSale();
    }

    public function getProductsByCategory($categoryId) {
        return $this->dao->getByCategoryId($categoryId);
    }

    public function searchProducts($keyword) {
        return $this->dao->searchByName($keyword);
    }

    public function updateStock($productId, $newStock) {
        if ($newStock < 0) {
            throw new Exception("Stock cannot be negative.");
        }
        return $this->dao->updateStock($productId, $newStock);
    }
}
?>