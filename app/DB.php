<?php


namespace app;

use mysqli;

class DB
{
    protected static string $db = DB_NAME;
    protected static string $table = DB_NAME;
    protected static string $pass = DB_PASSWORD;
    protected static string $user = DB_USERNAME;
    protected static string $db_url = DB_URL;
    
    protected static mysqli $connection;

    public static function setCreditionals($user, $pass)
    {
        self::$pass = $pass;
        self::$user = $user;
    }

    public static function connect()
    {
        self::$connection = new mysqli(self::$db_url, self::$user, self::$pass, self::$db);
    }

    public static function get_user_name($user_id)
    {
        $result = self::$connection->query("select `full_name` from `users` where `user_id` = $user_id");
        return ($result->num_rows == 0) ? false : $result->fetch_assoc()['full_name'];
    }

    public static function get_user_phone($user_id)
    {
        $result = self::$connection->query("select `phone` from `users` where `user_id` = $user_id");
        return ($result->num_rows == 0) ? false : $result->fetch_assoc()['phone'];
    }
    public static function get_user_id_by_phone($phone)
    {
        $result = self::$connection->query("select `phone` from `users` where `phone` = $phone");
        return ($result->num_rows == 0) ? false : $result->fetch_assoc()['phone'];
    }

    public static function get_user($user_id)
    {
        return self::$connection->query("select * from `" . self::$table . "` where `user_id` = $user_id")->fetch_assoc();
    }

    public static function get_user_info($user_id)
    {
        return self::$connection->query("select * from `users` where `user_id` = $user_id")->fetch_assoc();
    }

    public static function add_user($phone)
    {
        return self::$connection->query("insert into " . self::$table . "(phone) values($phone)");
    }

    static function set_user_fullname($user_id, $name)
    {
        $result = self::$connection->query("select * from `users` where `user_id` = $user_id");
        $sql = ($result->num_rows != 0) ? "update users set full_name = '$name' where user_id = $user_id" : "insert into users(user_id,full_name) values ($user_id, '$name')";
        return self::$connection->query($sql);
    }


    static function set_user_phone($user_id, $phone)
    {
        $sql = "update users set phone = '$phone' where user_id = $user_id";
        return self::$connection->query($sql);
    }

    static function update_user($user_id, $column, $value)
    {
        $sql = "update users set " . $column . " = '$value' where user_id = $user_id";
        return self::$connection->query($sql);
    }

    static function set_username($user_id, $username)
    {
        $sql = "update users set username = '$username' where user_id = $user_id";
        return self::$connection->query($sql);
    }

    public static function get($user_id, $column)
    {
        $sql = "select " . $column . " from users where user_id = $user_id";
        return self::$connection->query($sql)->fetch_assoc()[$column];
    }
}
