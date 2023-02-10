<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Product;
use App\Models\Transactions;
use App\Models\Comment;
use App\Helpers\ResponseFormatter;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::count();
        $products = Product::count();
        $users = User::count();
        $categories = Category::count();
        $transactions = Transaction::count();
        $comments = Comment::count();

        return response()->json([
            'success' => true,
            'message' => 'List Count Table',
            'data' => [
                'posts' => $posts,
                'categories' => $categories,
                'products' => $products,
                'transactions' => $transactions,
                'comments' => $comments,
                'users' => $users
            ],
        ], 200);
    }

    public function singlePost()
    {
        $post = Post::inRandomOrder()->first();

        return ResponseFormatter::success($post, 'Data Posts Berhasil di Dapatkan');
    }

    public function singleProduct()
    {
        $post = Product::inRandomOrder()->first();

        return ResponseFormatter::success($post, 'Data Posts Berhasil di Dapatkan');
    }
}
