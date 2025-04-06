<?php
require_once 'BaseDao.php';

class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("order_items");
    }

    protected function getPrimaryKey() {
        return "OrderItemID";
    }
}
?>
