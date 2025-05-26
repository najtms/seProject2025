<?php
require_once 'BaseDao.php';

class WishlistDao extends BaseDao {
    public function __construct() {
        parent::__construct("wishlist");
    }

    protected function getPrimaryKey() {
        return "WishlistID";
    }
}
?>

