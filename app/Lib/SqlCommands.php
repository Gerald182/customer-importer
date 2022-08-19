<?php 

namespace App\Lib;

use App\Lib\DBConnection;
use Doctrine\DBAL\DriverManager;

class SqlCommands extends DBConnection{

    public function selectAll($table)
    {
        $conn = new DBConnection();
        $stmt = $conn->getDBConnection()->prepare('SELECT * FROM '.$table);
        $resultSet = $stmt->executeQuery();
        $datas = $resultSet->fetchAllAssociative();
        return $datas;
    }

    public function selectData($table, $key, $value)
    {
        $conn = new DBConnection();
        $stmt = $conn->getDBConnection()->prepare('SELECT * FROM '.$table.' WHERE '.$key.' = ?');
        $resultSet =$stmt->executeQuery([$value]);
        $dbData = $resultSet->fetchAllAssociative();
        return $dbData;
    }

    public function insertData($table, $array)
    {
        $conn = new DBConnection();
        $columns = $prep = '';
        $arr = [];
        foreach ($array as $key => $value) {
            $columns = $columns . $key;
            $prep = $prep.'?';
            if ($key !== array_key_last($array)) {
                $columns = $columns.', ';
                $prep = $prep.', ';
            }
            array_push($arr, $value);
        }

        $stmt=$conn->getDBConnection()->prepare('INSERT INTO '.$table.' ('.$columns.') VALUES('.$prep.')');
        $result=$stmt->executeQuery($arr);
    }

    public function updateData($table, $array, $key, $value)
    {
        $conn = new DBConnection();
        $conn->getDBConnection()->update($table, $array, ['email' => $customer['email']]);
    }

}