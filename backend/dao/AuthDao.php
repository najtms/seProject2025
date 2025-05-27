<?php
require_once __DIR__ . '/BaseDao.php';

class AuthDao extends BaseDao
{
    protected $table_name;


    public function __construct()
    {
        parent::__construct("users");
    }

    protected function getPrimaryKey()
    {
        return "UserID";
    }
    public function get_user_by_email($email)
    {
        $query = "SELECT u.Email, u.Password, u.RoleID
                      FROM users u 
                      WHERE Email = :Email";
        return $this->query_unique($query, ['Email' => $email]);
    }
    public function get_role_by_name($name)
    {
        $query = "SELECT roleID FROM roles WHERE RoleName = :name";
        return $this->query_unique($query, ['name' => $name]);
    }
}