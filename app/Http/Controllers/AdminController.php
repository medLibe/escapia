<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('home', [
            'page'  => 'Dashboard',
        ]);
    }

    public function user()
    {
        return view('user', [
            'page'  => 'Users',
        ]);
    }

    public function item()
    {
        $getLastItemCode = Item::select('item_code')->orderBy('id', 'desc')->first();

        if($getLastItemCode === null){
            $item_code = 'ITM00001';
        }else{
            $increment = intval(substr($getLastItemCode->item_code, 4) + 1);
            $item_code = 'ITM' . str_pad($increment, 5, '0', STR_PAD_LEFT);

        }

        return view('item', [
            'page'      => 'Items',
            'item'      => Item::where('is_active', 1)->get(),
            'item_code' => $item_code,
        ]);
    }

    public function adjustment()
    {
        return view('adjustment', [
            'page'              => 'Adjustment',
            'transaction_id'    => $this->generateTransactionID(),
            'item'              => Item::where('is_active', 1)->get(),
        ]);
    }

    public function generateTransactionID()
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for($i = 0; $i < 10; $i++){
            $index = rand(0, strlen($chars) - 1);
            $randomString .= $chars[$index];
        }

        return $randomString;
    }

    public function report()
    {
        return view('report', [
            'page'      => 'Report',
            'item'      => Item::where('is_active', 1)->get(),
        ]);
    }
}
