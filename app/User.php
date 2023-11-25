<?php


namespace app;

class User extends DB
{
    // private $id = null;
    private $phone;
    private $id;

    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /***
        $garden['location'];
        $garden['area'];
        $garden['user_id'];
        $garden['status'];
        $garden['description'];
        $garden['price_for_kg'];
        $garden['pictures'];
        $garden['filters'];
     */
    public function add_garden($garden)
    {
        $garden->user_phone = $this->phone;

        $sql = "INSERT INTO `gardens` (`location`, `area`, `user_phone`, `status`, `description`, `price_for_kg`, `pictures`, `filters`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = self::$connection->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssssss", $garden->location, $garden->area, $garden->user_phone, $garden->status, $garden->description, $garden->price_for_kg, $garden->pictures, $garden->filters);
        
        // Execute the statement
        $stmt->execute();
        return self::$connection->insert_id;
    }

    public function generate_garden($garden, $area){
        // $garden->user_phone = $this->phone;

        $sql = "INSERT INTO `gardens` (`location`, `title`, `area`, `count`, `user_phone`, `status`, `description`, `price_for_kg`, `pictures`, `filters`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = self::$connection->prepare($sql);
        
        $garden->location = $garden->location[rand(0, count($garden->location) - 1)];
        $garden->user_phone = $garden->user_phone[rand(0, count($garden->user_phone) - 1)];
        $garden->status = $garden->status[rand(0, count($garden->status) - 1)];
        $garden->description = $garden->description[rand(0, count($garden->description) - 1)];
        $garden->price_for_kg = $garden->price_for_kg[rand(0, count($garden->price_for_kg) - 1)];
        $garden->filters = $garden->filters[rand(0, count($garden->filters) - 1)];
        $garden->title = $garden->title[rand(0, count($garden->title) - 1)];
        $garden->count = rand(1, 100);
        
        // Sample array of fruit names
        $garden->pictures = "https://m3574.myxvest.ru/bogbor". "/pictures" . '/' . $garden->title . '.jpg';

        $area = json_encode($area);

        // Bind parameters
        $stmt->bind_param("sssissssss", $garden->location, $garden->title, $area, $garden->count, $garden->user_phone, $garden->status, $garden->description, $garden->price_for_kg, $garden->pictures, $garden->filters);
  
        // Execute the statement
        $stmt->execute();
        return self::$connection->insert_id;
    }

    public function get_garden($id){
        $sql = "SELECT * FROM `gardens` WHERE `id`=$id";
        return self::$connection->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
    }
    public function get_gardens(){
        $sql = "SELECT * FROM `gardens`";
        return self::$connection->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function save_or_ignore()
    {
        if (empty(DB::get_user_id_by_phone($this->phone))) {
            return DB::add_user($this->phone);
        }
        return 1;
    }

    public function get_name()
    {
        return DB::get_user_name($this->id);
    }

    public function get_phone()
    {
        return DB::get_user_phone($this->id);
    }


    public function set_name($name)
    {
        return DB::set_user_fullname($this->id, $name);
    }


    public function set_phone($phone)
    {
        return DB::set_user_phone($this->id, $phone);
    }

    public function set($column, $value)
    {
        DB::update_user($this->id, $column, $value);
    }

    function get_info(): ?array
    {
        return DB::get_user_info($this->id);
    }
}
