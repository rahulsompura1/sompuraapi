<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    /**
     * Get all the Categories from the database table.
     */

    public function getCartByID(Request $request)
    {
        $userID = $request->get('user_id');
        $shoppingCart = ShoppingCart::where('user_id', $userID)->get()->toJson(JSON_PRETTY_PRINT);
        return response($shoppingCart, 200);
    }
    /**
     * Get Particular shoppingCart by ID
     */
    public function getShoppingCart($id)
    {
        if (ShoppingCart::where('id', $id)->exists()) {
            $shoppingCart = ShoppingCart::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($shoppingCart, 200);
        } else {
            return response()->json([
                "message" => "ShoppingCart not found"
            ], 404);
        }
    }
    /**
     * Create new shoppingCart
     */
    public function addToShoppingCart(Request $request)
    {
        $shoppingCart = new ShoppingCart;
        $shoppingCart->price = $request->address;
        $shoppingCart->discount = $request->city;
        $shoppingCart->product_ids = $request->delivery_time;
        $shoppingCart->user_id = $request->user_id;
        $shoppingCart->save();

        return response()->json([
            "message" => "ShoppingCart record created"
        ], 201);
    }

    /**
     * Update a shoppingCart by ID
     */
    public function updateShoppingCart(Request $request, $id) {
        if (ShoppingCart::where('id', $id)->exists()) {
          $shoppingCart = ShoppingCart::find($id);
  
          $shoppingCart->price = empty($request->price) ? $shoppingCart->price : $shoppingCart->price;
          $shoppingCart->discount = empty($request->discount) ? $shoppingCart->discount : $shoppingCart->discount;
          $shoppingCart->product_ids = empty($request->product_ids) ? $shoppingCart->product_ids : $shoppingCart->delproduct_idsivery_time;
          $shoppingCart->user_id = empty($request->user_id) ? $shoppingCart->user_id : $shoppingCart->user_id;
          $shoppingCart->save();
  
          return response()->json([
            "message" => "records updated successfully"
          ], 200);
        } else {
          return response()->json([
            "message" => "ShoppingCart not found"
          ], 404);
        }
      }

      /**
       * Delete a shoppingCart by ID.
       */
      public function deleteShoppingCart ($id) {
        if(ShoppingCart::where('id', $id)->exists()) {
            
          $shoppingCart = ShoppingCart::find($id);
          $shoppingCart->delete();
  
          return response()->json([
            "message" => "records deleted"
          ], 202);
        } else {
          return response()->json([
            "message" => "ShoppingCart not found"
          ], 404);
        }
      }
}
