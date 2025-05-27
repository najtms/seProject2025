<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService extends BaseService {
    public function __construct() {
        parent::__construct(new ProductDao());
    }

    // Renamed from addProduct() to insert() to match route calls
    public function insert($productData) {
        // Validate required fields
        $required = ['Name', 'Price', 'CategoryID'];
        foreach ($required as $field) {
            if (empty($productData[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Validate business rules
        if ($productData['Price'] <= 0) {
            throw new Exception("Product price must be positive.");
        }
        if ($productData['Stock'] < 0) {
            throw new Exception("Product stock cannot be negative.");
        }

        // Convert boolean to integer for database
        if (isset($productData['OnSale'])) {
            $productData['OnSale'] = $productData['OnSale'] ? 1 : 0;
        }

        return $this->dao->insert($productData);
    }

    // Keep all your other methods
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

    // Add these methods to support all routes
    public function getAll() {
        return $this->dao->getAll();
    }

    public function update($id, $productData) {
        return $this->dao->update($id, $productData);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
?>