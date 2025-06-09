<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../Dao/CartItemsDao.php';


class CartItemsService extends BaseService
{

    public function __construct()
    {
        $dao = new CartItemsDao();

        parent::__construct($dao);
    }


    public function getCartItems($cart_ID)
    {
        return $this->dao->getCartItems($cart_ID);
    }

    public function addCartItem($cart_ID, $product_ID, $quantity)
    {
        return $this->dao->addCartItem($cart_ID, $product_ID, $quantity);
    }

    public function deleteCartItem($cart_item_ID, $user_ID)
    {
        return $this->dao->deleteCartItem($cart_item_ID, $user_ID);
    }
}
