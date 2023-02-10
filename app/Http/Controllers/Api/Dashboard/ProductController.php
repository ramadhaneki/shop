<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->when(request()->q, function($products) {
            $products = $products->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);

        //return with Api Resource
        return ResponseFormatter::success($products, 'Data Post berhasil di dapatkan');
    }
}
