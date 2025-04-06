<?php
require_once 'BaseDao.php';

class CartDao extends BaseDao {
    public function __construct() {
        parent::__construct("cart");
    }

    public function getByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM cart WHERE UserID = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteByUserProduct($userId, $productId) {
        $stmt = $this->connection->prepare("DELETE FROM cart WHERE UserID = :userId AND ProductID = :productId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':productId', $productId);
        return $stmt->execute();
    }
}
?>
