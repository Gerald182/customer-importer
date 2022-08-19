<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\SqlCommands;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cmd = new SqlCommands();
        $customers = $cmd->selectAll('customers');

        $results =['results'=>[]];

        foreach ($customers as $customer) {
            $newData['id'] = $customer['id'];
            $newData['fullname'] = $customer['firstname']." ".$customer['lastname'];
            $newData['email'] = $customer['email'];
            $newData['country'] = $customer['country'];
            array_push($results['results'], $newData);
        }

        return response()->json($results);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cmd = new SqlCommands();
        $customer = $cmd->selectData('customers', 'id', $id);
        $result = [
            'results'=>[
                'fullname' => $customer[0]['firstname']." ".$customer[0]['lastname'],
                'email' => $customer[0]['email'],
                'username' => $customer[0]['username'],
                'gender' => $customer[0]['gender'],
                'country' => $customer[0]['country'],
                'city' => $customer[0]['city']
            ]
        ];
        return response()->json($result);
    }
}
