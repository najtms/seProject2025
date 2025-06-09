<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService extends BaseService {
    public function __construct() {
        parent::__construct(new ProductDao());
    }

    public function addProduct($productData) {
        // Service-level validation
        if (empty($productData)) {
            throw new Exception("Product data is required");
        }

        // Format and sanitize data
        if (isset($productData['Price'])) {
            $productData['Price'] = (float)$productData['Price'];
        }
        if (isset($productData['Stock'])) {
            $productData['Stock'] = (int)$productData['Stock'];
        }
        if (isset($productData['CategoryID'])) {
            $productData['CategoryID'] = (int)$productData['CategoryID'];
        }
        if (isset($productData['OnSale'])) {
            $productData['OnSale'] = $productData['OnSale'] ? 1 : 0;
        }

        return $this->dao->addProduct($productData);
    }

    public function deleteProduct($productId){
        return $this->dao->deleteProduct($productId);
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

    public function getById($id){
        return $this->dao->getById($id);
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