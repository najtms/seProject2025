<?php
require_once 'CartDao.php';
require_once 'CartItemsDao.php';
require_once 'ProductDao.php';
require_once 'CartDao.php';
require_once 'UserDao.php';
require_once 'AuthDao.php';


$tset = new UserDao();

print_r($tset->getUserOrders(27));


