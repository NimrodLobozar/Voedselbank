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

        // Fetch all produce items for all packages
        $produceItems = DB::table('food_package_produce')
            ->join('produce', 'food_package_produce.produce_id', '=', 'produce.id')
            ->select(
                'food_package_produce.food_package_id',
                'produce.name as produce_name',
                'food_package_produce.quantity'
            )
            ->get()
            ->groupBy('food_package_id');

        // Attach produce items to each package
        foreach ($packages as $package) {
            $package->produce_items = $produceItems->get($package->id, collect());
        }

        return view('food_packages.index', compact('packages'));
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customer,id',
        'package_name' => 'required|string|max:100',
        'assembled_at' => 'required|date',
        'distribution_date' => 'required|date',
        'pickup_time' => 'nullable',
        'produce' => 'required|array',
    ]);

    // Prepare produce_items from form input
    $produceItems = [];
    foreach ($validated['produce'] as $item) {
        if (isset($item['id']) && isset($item['quantity']) && $item['id'] && $item['quantity']) {
            $produceItems[] = [
                'id' => $item['id'],
                'amount' => $item['quantity'],
            ];
        }
    }

    if (empty($produceItems)) {
        return back()->withInput()->withErrors(['produce' => 'Selecteer ten minste één product en geef een hoeveelheid op.']);
    }

    // Create food package
    $packageId = DB::table('food_package')->insertGetId([
        'customer_id' => $validated['customer_id'],
        'package_name' => $validated['package_name'],
        'assembled_at' => $validated['assembled_at'],
        'distribution_date' => $validated['distribution_date'],
        'pickup_time' => $validated['pickup_time'],
        'created_at' => now(),
        'updated_at' => now(),
        'datum_aangemaakt' => now(),
        'datum_gewijzigd' => now(),
    ]);

    // Insert produce items into food_package_produce pivot table
    foreach ($produceItems as $item) {
        DB::table('food_package_produce')->insert([
            'food_package_id' => $packageId,
            'produce_id' => $item['id'],
            'quantity' => $item['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('food_packages.index')->with('success', 'Voedselpakket succesvol aangemaakt.');
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

public function show($id)
{
    $package = DB::table('food_package')
        ->join('customer', 'food_package.customer_id', '=', 'customer.id')
        ->select(
            'food_package.*',
            DB::raw("CONCAT(customer.first_name, ' ', IFNULL(customer.middle_name, ''), ' ', customer.last_name) as klantnaam")
        )
        ->where('food_package.id', $id)
        ->first();

    if (!$package) {
        abort(404);
    }

    $produceItems = DB::table('food_package_produce')
        ->join('produce', 'food_package_produce.produce_id', '=', 'produce.id')
        ->select('produce.name as produce_name', 'food_package_produce.quantity')
        ->where('food_package_produce.food_package_id', $id)
        ->get();

    $package->produce_items = $produceItems;

    return view('food_packages.show', compact('package'));
}

}