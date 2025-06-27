<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodPackageController extends Controller
{
    public function index()
    {
        $packages = DB::table('food_package')
            ->join('customer', 'food_package.customer_id', '=', 'customer.id')
            ->select(
                'food_package.*',
                DB::raw("CONCAT(customer.first_name, ' ', IFNULL(customer.middle_name, ''), ' ', customer.last_name) as klantnaam")
            )
            ->orderBy('food_package.distribution_date', 'desc')
            ->get();

        return view('food_packages.index', compact('packages'));
    }
}