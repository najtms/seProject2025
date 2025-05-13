<?php
require_once 'BaseDao.php';

class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct("products");
    }

    protected function getPrimaryKey() {
        return "ProductID";
    }

    public function getOnSale() {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE OnSale = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategoryId($categoryId) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE CategoryID = :categoryId");
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByName($keyword) {
        $searchTerm = '%' . $keyword . '%';
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE Name LIKE :keyword");
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStock($productId, $newStock) {
        $stmt = $this->connection->prepare("UPDATE products SET Stock = :stock WHERE ProductID = :id");
        $stmt->bindParam(':stock', $newStock);
        $stmt->bindParam(':id', $productId);
        return $stmt->execute();
    }

    public function getPaged($limit, $offset) {
        $stmt = $this->connection->prepare("SELECT * FROM products LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
