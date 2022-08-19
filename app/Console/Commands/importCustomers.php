<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Lib\UrlBuilder;
use App\Lib\SqlCommands;

class importCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from a 3rd party data provider and save to database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cmd = new SqlCommands();
        $table_name = 'customers';
        $customers = $this->getCustomers();
        $inserted = $updated = 0;

        foreach($customers["results"] as $customer){
            $dbCustomer = $cmd->selectData($table_name, 'email', $customer['email']);

            if (!$dbCustomer) {
                $data = [
                    "firstname" => $customer['name']['first'],
                    "lastname"  => $customer['name']['last'],
                    "email"     => $customer['email'],
                    "username"  => $customer['login']['username'],
                    "password"  => $customer['login']['md5'],
                    "gender"    => $customer['gender'],
                    "country"   => $customer['location']['country'],
                    "city"      => $customer['location']['city'],
                ];
                $cmd->insertData($table_name, $data);
                $inserted++;
            }else{
                $newCustomerData = [
                    "firstname" => $customer['name']['first'],
                    "lastname" => $customer['name']['last'],
                    "username" => $customer['login']['username'],
                    "password" => $customer['login']['md5'],
                    "gender" => $customer['gender'],
                    "country" => $customer['location']['country'],
                    "city" => $customer['location']['city'],
                ];
                $cmd->updateData($table_name, $newCustomerData, 'email', $customer['email']);
                $updated++;
            }
        }
        if($inserted) {
            echo 'Inserted '.$inserted.' customers';
            if ($updated) {
                echo ' and updated '.$updated.' customers';
            }
        }
        if ($inserted===0 && $updated>0) {
            echo ' Updated '.$updated.' customers';
        }
    }

    public function getCustomers()
    {
        $builder = new UrlBuilder();
        $url = 'https://randomuser.me/api/';
        $urlParam = [
            "results" => 1,
            "nat" => 'au',
            "inc" => ['name','gender','location','email','login','nat'],
            "noinfo" => ''
        ];

        $client = new Client();
        $res = $client->request('GET', $builder->buildURL($url,$urlParam));

        return json_decode($res->getBody(),true);
    }
}
