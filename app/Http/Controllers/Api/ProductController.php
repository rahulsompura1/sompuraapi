<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL as Url;



class ProductController extends Controller
{
    /**
     * Get all the Categories from the database table.
     */
    public function getAllProducts(Request $request)
    {
        $company_id = \Auth::user()->id;
        $category_id = $request->category_id;

        $products =  Product::where('category_id', $category_id)->orderBy('sequence')->get()->toArray();


        return response()->json(['product' => $products, 200]);
    }

    /**
     * Get Particular product by ID
     */
    public function getProduct($id)
    {
        if (Product::where('_id', $id)->exists()) {
            $product = Product::where('_id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($product, 200);
        } else {
            return response()->json(["message" => "Product not found"], 404);
        }
    }

    /**
     * Create new product
     */
    public function createProduct(Request $request)
    {
        $validator = \Validator::make($request->all(), (new Product)->rules);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->messages()], 201);
        }

        $imageArr = $request->file('image');
        $filesArr = [];
        if (!empty($imageArr)) {
            foreach ($imageArr as $image) {
                $image_name = $image->getClientOriginalName();
                $storage = Storage::putFileAs("public/photos", $image, $image_name);
                $filesArr[] = Url::to(Storage::url($storage));
            }
        }
        $requestArr = [
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category,
            'company_id' => \Auth::user()->id,
            'prices' => json_decode($request->prices, true),
            'images' => $filesArr,
            'sequence' => Product::count()
        ];
        if ($request->has('_id')) {
            $product = Product::where('_id', $request->get('_id'));
            $imgArr = $product->first()->images;
            if(!empty($imgArr)){
                $requestArr['images'] = array_merge($filesArr,$imgArr );
            }
             $update =  $product->update($requestArr);
            return response()->json([
                "message" => "Product record updated",
                "product" => $product->first()
            ], 200);
        } else {
            $product = Product::create($requestArr);
            return response()->json([
                "message" => "Product record created",
                "product" => $product
            ], 200);
        }
    }
    /**
     * Create new product
     */
    public function updateProductCategory(Request $request, $id)
    {
        $product = Product::find($id);
        $product->category_id = $request->category_id;
        $product->save();
        return response()->json([
            "message" => "Product record updated"
        ], 200);
    }

    /**
     * Update a product by ID
     */
    public function updateProduct(Request $request, Product $product)
    {

        if ($product->update($request->all())) {
            return response()->json(["message" => "Records updated successfully"], 200);
        } else {
            return response()->json(["message" => "Something wrong happen. Please try again"], 504);
        }
    }

    /**
     * Delete a product by ID.
     */
    public function deleteProduct(Product $product)
    {
        if ($product->delete()) {
            return response()->json(["message" => "records deleted"], 200);
        } else {
            return response()->json(["message" => "Product not found"], 404);
        }
    }
    public function updateSequence(Request $request)
    {
        $categories = $request->get('categories');
        foreach ($categories as $catKey => $cat) {
            foreach ($cat['product'] as $key => $val) {
                $product = Product::find($val['_id']);
                $product->sequence = $catKey + $key;
                $product->save();
            }
        }
    }
}
