<?php 

namespace App\Lib;

use Doctrine\DBAL\DriverManager;

class DBConnection{

    public function getDBConnection()
    {
        $params = [
            'host'=> env('DB_HOST'),
            'user'=> env('DB_USERNAME'),
            'password'=> env('DB_PASSWORD'),
            'dbname'=> env('DB_DATABASE'),
            'driver'=> env('DB_DRIVER')??'mysqli'
        ];
        return DriverManager::getConnection($params);
    }

}