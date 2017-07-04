<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Log;
use App\Product;

class LogController extends Controller
{
    //
    public function index()
    {
//        $product_ids = Log::where('user_id', $user_id)->get(['product_id'])->toArray();
//        $products = Product::whereIn('id', $product_ids)->get();
//        var_dump($products->toArray());
        $products = DB::table('products')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('logs')
                    ->whereRaw('Products.id = Logs.product_id and Logs.user_id = 2');
            })
            ->get();
        $result = [
            'total_count' => $products->count(),
            'products' => [],
        ];
        foreach($products as $product) {
            $result['products'][] = [
                'product_id' => $product->id,
                'name' => $product->name,
            ];
        }
        return response()->json($result);
    }
}
