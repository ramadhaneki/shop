<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::when(request()->q, function ($categories) {
            $categories = $categories->where('name', 'LIKE', '%'. request()->q . '%');
        })->latest()->paginate(5);

        return ResponseFormatter::success($categories, 'Data Category Berhasil di Dapatkan');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'    => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name'     => 'required|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        //create category
        $category = Category::create([
            'image'=> $image->hashName(),
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);

        if ($category) {
            return ResponseFormatter::success($category, 'Data Category berhasil di Simpan');
        }

        
        return ResponseFormatter::error(null, 'Data Category Gagal Di Simpan', 500);
    }

    public function show($id)
    {
        $category = Category::whereId($id)->first();

        if ($category) {
            return ResponseFormatter::success($category, 'Detail Data Category');
        }

        return ResponseFormatter::error(null, 'Detail Data Category tidak ditemukan', 404);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|unique:categories,name,'.$category->id,
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, 'Nama Category Wajib Di Lengkapi', 500);
        }

        if ($request->file('image')) {
            Storage::disk('local')->delete('public/categories/' . basename($category->image));

            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());
    
            $category->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);

        if ($category) {
            //return success with Api Resource
            return ResponseFormatter::success($category, 'Data Category Berhasil Diupdate!');
        }

        return ResponseFormatter::error(null, 'Data Category Gagal Diupdate!', 500);
    }

    public function destroy(Category $category)
    {
        Storage::disk('local')->delete('public/categories/'.basename($category->image));

        if ($category->delete()) {
            return ResponseFormatter::success(null, 'Data Category Berhasil Dihapus!');
        }

        return ResponseFormatter::error(null, 'Data Category Gagal Dihapus!', 500);
    }
}
