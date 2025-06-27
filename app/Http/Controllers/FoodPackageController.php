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

public function create()
{
    $customers = DB::table('customer')
        ->select('id', DB::raw("CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) as full_name"))
        ->get();

    $produceItems = DB::table('produce')
        ->where('is_actief', true)
        ->where('amount', '>', 0)
        ->orderBy('expiry_date')
        ->get();

    return view('food_packages.create', compact('customers', 'produceItems'));
}

}