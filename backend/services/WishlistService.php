<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/WishlistDao.php';

class WishlistService extends BaseService {
    public function __construct() {
        parent::__construct(new WishlistDao());
    }

    public function addToWishlist($userId, $productId) {
        return $this->dao->insert([
            'UserID' => $userId,
            'ProductID' => $productId
        ]);
    }
}
?>