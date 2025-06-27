<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FoodPackage;
use App\Models\Produce;

class FoodPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('food_package')
            ->join('customer', 'food_package.customer_id', '=', 'customer.id')
            ->select(
                'food_package.*',
                DB::raw("CONCAT(customer.first_name, ' ', IFNULL(customer.middle_name, ''), ' ', customer.last_name) as klantnaam")
            );

        // Filtering
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where(DB::raw("CONCAT(customer.first_name, ' ', IFNULL(customer.middle_name, ''), ' ', customer.last_name)"), 'like', "%{$search}%")
                  ->orWhere('food_package.package_name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('food_package.status', $request->input('status'));
        }

        $packages = $query->orderBy('food_package.distribution_date', 'desc')->paginate(1);

        // Fetch all produce items for all packages (for current page only)
        $packageIds = $packages->pluck('id')->all();
        $produceItems = DB::table('food_package_produce')
            ->join('produce', 'food_package_produce.produce_id', '=', 'produce.id')
            ->select(
                'food_package_produce.food_package_id',
                'produce.name as produce_name',
                'food_package_produce.quantity'
            )
            ->whereIn('food_package_produce.food_package_id', $packageIds)
            ->get()
            ->groupBy('food_package_id');

        foreach ($packages as $package) {
            $package->produce_items = $produceItems->get($package->id, collect());
        }

        return view('food_packages.index', compact('packages'));
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customer,id',
        'package_name' => 'nullable|string|max:100',
        'assembled_at' => ['required', 'date', 'after_or_equal:today'],
        'distribution_date' => ['required', 'date', 'after_or_equal:today'],
        'pickup_time' => 'nullable',
        'produce' => 'required|array',
    ]);

    $produceItems = [];
    $shortCodes = [];
    foreach ($validated['produce'] as $item) {
        if (isset($item['id']) && isset($item['quantity']) && $item['id'] && $item['quantity']) {
            $produceItems[] = [
                'id' => $item['id'],
                'amount' => $item['quantity'],
            ];
            $produce = \App\Models\Produce::find($item['id']);
            if ($produce) {
                $prodShort = ucfirst(mb_substr($produce->name, 0, 3));
                $catShort = strtoupper(mb_substr($produce->category, 0, 2));
                $shortCodes[] = "{$prodShort}-{$catShort}";
            }
        }
    }

    if (empty($produceItems)) {
        return back()->withInput()->withErrors(['produce' => 'Selecteer ten minste één product en geef een hoeveelheid op.']);
    }

    // Stock check using Produce model
    foreach ($produceItems as $item) {
        $produce = Produce::find($item['id']);
        if (!$produce || !$produce->hasStock($item['amount'])) {
            $available = $produce ? $produce->amount : 0;
            return back()->withInput()->withErrors([
                'produce' => "Niet genoeg voorraad voor {$item['amount']}x van {$produce->name} (beschikbaar: {$available})."
            ]);
        }
    }

    // Auto-generate short package name if not provided
    $packageName = $validated['package_name'] ?? null;
    if (empty($packageName)) {
        $max = 3;
        $nameList = array_slice($shortCodes, 0, $max);
        $packageName = implode(' ', $nameList);
        if (count($shortCodes) > $max) {
            $packageName .= ' ...';
        }
    }

    try {
        $package = FoodPackage::create([
            'customer_id' => $validated['customer_id'],
            'package_name' => $packageName,
            'assembled_at' => $validated['assembled_at'],
            'distribution_date' => $validated['distribution_date'],
            'pickup_time' => $validated['pickup_time'],
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);
    } catch (\Exception $e) {
        return back()->withInput()->withErrors(['error' => 'Er is een fout opgetreden bij het opslaan van het voedselpakket.']);
    }

    foreach ($produceItems as $item) {
        $produce = Produce::find($item['id']);
        $package->produces()->attach($produce->id, [
            'quantity' => $item['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $produce->decrementStock($item['amount']);
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

public function edit($id)
{
    $package = DB::table('food_package')
        ->where('id', $id)
        ->first();

    if (!$package) {
        abort(404);
    }

    $customers = DB::table('customer')
        ->select('id', DB::raw("CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) as full_name"))
        ->get();

    $produceItems = DB::table('produce')
        ->where('is_actief', true)
        ->orderBy('expiry_date')
        ->get();

    $selectedProduce = DB::table('food_package_produce')
        ->where('food_package_id', $id)
        ->pluck('quantity', 'produce_id')
        ->toArray();

    return view('food_packages.edit', compact('package', 'customers', 'produceItems', 'selectedProduce'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customer,id',
        'package_name' => 'required|string|max:100',
        'assembled_at' => 'required|date',
        'distribution_date' => 'required|date',
        'pickup_time' => 'nullable',
        'status' => 'required|in:Assembled,Ready,Distributed,Cancelled',
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

    // Check stock for each produce item (add back old quantities first)
    $oldProduce = DB::table('food_package_produce')
        ->where('food_package_id', $id)
        ->get();

    foreach ($oldProduce as $old) {
        DB::table('produce')
            ->where('id', $old->produce_id)
            ->increment('amount', $old->quantity);
    }

    foreach ($produceItems as $item) {
        $produce = DB::table('produce')->where('id', $item['id'])->first();
        $available = $produce ? $produce->amount : 0;
        if ($item['amount'] > $available) {
            return back()->withInput()->withErrors([
                'produce' => "Niet genoeg voorraad voor {$item['amount']}x van {$produce->name} (beschikbaar: {$available})."
            ]);
        }
    }

    // Update food package
    DB::table('food_package')->where('id', $id)->update([
        'customer_id' => $validated['customer_id'],
        'package_name' => $validated['package_name'],
        'assembled_at' => $validated['assembled_at'],
        'distribution_date' => $validated['distribution_date'],
        'pickup_time' => $validated['pickup_time'],
        'status' => $validated['status'],
        'updated_at' => now(),
        'datum_gewijzigd' => now(),
    ]);

    // Remove old produce links
    DB::table('food_package_produce')->where('food_package_id', $id)->delete();

    // Insert new produce items and decrement stock
    foreach ($produceItems as $item) {
        DB::table('food_package_produce')->insert([
            'food_package_id' => $id,
            'produce_id' => $item['id'],
            'quantity' => $item['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('produce')
            ->where('id', $item['id'])
            ->decrement('amount', $item['amount']);
    }

    return redirect()->route('food_packages.index')->with('success', 'Voedselpakket succesvol bijgewerkt.');
}

public function destroy($id)
{
    $package = DB::table('food_package')->where('id', $id)->first();

    if (!$package) {
        return redirect()->route('food_packages.index')->with('error', 'Voedselpakket niet gevonden.');
    }

    if ($package->status === 'Distributed') {
        return redirect()->route('food_packages.index')->with('error', 'Uitgeleverde pakketten mogen niet verwijderd worden.');
    }

    // Delete related produce links
    DB::table('food_package_produce')->where('food_package_id', $id)->delete();

    // Delete the food package
    DB::table('food_package')->where('id', $id)->delete();

    return redirect()->route('food_packages.index')->with('success', 'Voedselpakket succesvol verwijderd.');
}

}