<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    /**
     * Get all the Categories from the database table.
     */
    public function getAllCategories(Request $request) {
        // $companyID = $request->get('company_id');

        $category = Category::orderBy('sequence');
        if ($request->has('company_id') && !empty($request->get('company_id'))) {
            $category = $category->where('company_id', $request->company_id);
        }
        $category = $category->get()->toJson(JSON_PRETTY_PRINT);
        return response($category, 200);
    }

    /**
     * Get all the Categories With product from the database table.
     */
    public function getAllCategoriesWithProduct(Request $request) {

        $categories = Category::orderBy('sequence');
        if ($request->has('company_id') && !empty($request->get('company_id'))) {
            $categories = $categories->where('company_id', $request->company_id);
        }

        $categories = $categories->get();
        foreach ($categories as $key => $category) {
            $product = product::where('category_id', $category->_id);
            if ($request->has('keyword') && !empty($request->get('keyword'))) {
                $product = $product->where('name', 'like', '%' . $request->keyword . '%');
            }
            $product = $product->get();
            if (empty($product->toArray()) && $request->has('keyword') && !empty($request->get('keyword'))) {
                unset($categories[$key]);
            } else {
                $categories[$key]['product'] = $product;
                if (empty($product->toArray()) && $request->has('customer') && $request->get('customer') == true) {
                    unset($categories[$key]);
                }
            }
        }
        return response($categories->toJson(JSON_PRETTY_PRINT), 200);
    }

    /**
     * Get Particular category by ID
     */
    public function getCategory($id) {
        if (Category::where('_id', $id)->exists()) {
            $category = Category::where('_id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($category, 200);
        } else {
            return response()->json([
                        "message" => "Category not found"
                            ], 404);
        }
    }

    /**
     * Create new category
     */
    public function createCategory(Request $request) {
        $category = new Category;
        $category->name = $request->name;
        $category->company_id = $request->company_id;
        $category->save();

        return response()->json([
                    "message" => "Category record created",
                    'category' => $category
                        ], 201);
    }

    /**
     * Update a category by ID
     */
    public function updateCategory(Request $request, $id) {
        if (Category::where('_id', $id)->exists()) {
            $category = Category::find($id);

            $category->name = !empty($request->name) ? $request->name : $category->name;
            $category->company_id = !empty($request->company_id) ? $request->company_id : $category->company_id;
            $category->save();

            return response()->json([
                        "message" => "records updated successfully"
                            ], 200);
        } else {
            return response()->json([
                        "message" => "Category not found"
                            ], 404);
        }
    }

    /**
     * Delete a category by ID.
     */
    public function deleteCategory($id) {
        if (Category::where('_id', $id)->exists()) {

            $product = product::where('category_id', $id)->count();
            if ($product <= 0) {
                $category = Category::where('_id', $id);
                $category->delete();
                return response()->json([
                            "message" => "records deleted"
                                ], 202);
            } else {
                return response()->json([
                            "message" => "Category associated with product"
                                ], 203);
            }
        } else {
            return response()->json([
                        "message" => "Category not found"
                            ], 404);
        }
    }

    public function updateSequence(Request $request) {
        $categories = $request->get('categories');
        foreach ($categories as $key => $val) {
            $category = Category::find($val['_id']);
            $category->sequence = $key;
            $category->save();
        }
    }

}
