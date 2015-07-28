<?php
namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductsApiController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::table('products')->select('id', 'name', 'price', 'in_stock')->get();

        return response()->json($products);
    }

    public function show(Request $request, $id)
    {
        $product = DB::table('products')->where('id', $id)->first();

        if (empty($product))
            abort(404);

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $price = $request->input('price');
        $in_stock = $request->input('in_stock');

        $id = DB::table('products')->insertGetId(
            [
                'name' => $name,
                'price' => $price,
                'in_stock' => $in_stock,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        );

        $product = DB::table('products')->where('id', $id)->first();

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = DB::table('products')->where('id', $id)->first();

        if (empty($product))
            abort(404);

        $update = [];

        if (!empty(($name = $request->input('name'))))
            $update['name'] = $name;

        if (!empty(($price = $request->input('price'))))
            $update['price'] = $price;

        if (!empty(($in_stock = $request->input('in_stock'))))
            $update['in_stock'] = $in_stock;

        DB::table('products')->where('id', $id)->update($update);

        $product = DB::table('products')->where('id', $id)->first();

        return response()->json($product);
    }

    public function destroy(Request $request, $id)
    {
        $product = DB::table('products')->where('id', $id)->first();

        if (empty($product))
            abort(404);

        DB::table('products')->where('id', $id)->delete();

        return;
    }
}