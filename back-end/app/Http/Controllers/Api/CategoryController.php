<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::orderBy("order");
        if ($request->q) {
            $query = $query->search($request->q);
        }

        if ($request->perPage) {
            $query = $query->paginate($request->perPage)->appends($request->toArray());
        } else {
            $query = $query->get();
        }

        return CategoryResource::collection($query);
    }

    public function get(Category $category)
    {
        return new CategoryResource($category);
    }

    public function posts(Request $request, Category $category)
    {
        $query = $category->posts()->orderBy('created_at', 'desc');

        if ($request->q) {
            $query = $query->search($request->q);
        }

        $perPage = $request->perPage ?: 6;

        return PostResource::collection($query->paginate($perPage)->appends($request->toArray()));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'slug' => 'required|alpha_dash|unique:categories',
            'order' => 'required|numeric'
        ]);

        $request->slug = str_slug($request->slug);

        $category = Category::create([
            'slug' => $request->slug,
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order,
        ]);

        if ($category) {
            return response()->json([
                'success' => true,
                'message' => 'Category has been created successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category creation failed.'
        ]);
    }

    public function delete(Category $category)
    {
        $deleted = $category->delete();
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Category has been deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category deletion failed.'
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'slug' => 'required|alpha_dash|unique:categories,slug,' . $category->id . ',id,deleted_at,NULL',
            'order' => 'required|numeric'
        ]);

        $request->slug = str_slug($request->slug);

        $saved = $category->update([
            'slug' => $request->slug,
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order,
        ]);

        if ($saved) {
            return response()->json([
                'success' => true,
                'message' => 'Category has been saved successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category update failed.'
        ]);
    }
}
