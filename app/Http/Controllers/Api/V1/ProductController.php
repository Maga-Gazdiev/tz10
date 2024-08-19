<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $response = Http::get('https://dummyjson.com/products/search', [
            'q' => $request->product
        ]);

        if ($response->failed()) {
            return response()->json(['error' => "Не удалось получить продукт по запросу: " . $request->product], 500);
        }

        $products = $response->json('products');

        foreach ($products as $product) {
            Product::create([
                'title' => $product['title'],
                'description' => $product['description'],
                'price' => $product['price']
            ]);
        }

        return response()->json(['message' => $request->product . " успешно добавлен"], 201);
    }

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }
}