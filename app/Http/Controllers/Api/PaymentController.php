<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ShoppingCart;
use PDF;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Order;
class PaymentController extends Controller
{
  /**
   * Get all the Categories from the database table.
   */

  public function getAllPaymentByuserID(Request $request)
  {
    $userID = $request->get('user_id');
    $payment = Payment::where('user_id', $userID)->get()->toJson(JSON_PRETTY_PRINT);
    return response($payment, 200);
  }
  /**
   * Get Particular payment by ID
   */
  public function getPayment($id)
  {
    if (Payment::where('id', $id)->exists()) {
      $payment = Payment::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
      return response($payment, 200);
    } else {
      return response()->json([
        "message" => "Payment not found"
      ], 404);
    }
  }
  /**
   * Create new payment
   */
  public function createPayment(Request $request)
  {
    $payment = new Payment;
    $payment->cart_id = $request->cart_id;
    $payment->form_field_id = $request->form_field_id;
    $payment->transaction_id = $request->transaction_id;
    $payment->price = $request->price;
    $payment->created_by = $request->created_by;
    $payment->save();

    return response()->json([
      "message" => "Payment record created"
    ], 201);
  }

  /**
   * Update a payment by ID
   */
  public function updatePayment(Request $request, $id)
  {
    if (Payment::where('id', $id)->exists()) {
      $payment = Payment::find($id);

      $payment->cart_id = empty($request->cart_id) ? $payment->cart_id : $payment->cart_id;
      $payment->form_field_id = empty($request->form_field_id) ? $payment->form_field_id : $payment->form_field_id;
      $payment->transaction_id = empty($request->transaction_id) ? $payment->transaction_id : $payment->transaction_id;
      $payment->price = empty($request->price) ? $payment->price : $payment->price;
      $payment->user_id = empty($request->user_id) ? $payment->user_id : $payment->user_id;
      $payment->created_by = empty($request->created_by) ? $payment->created_by : $payment->created_by;
      $payment->save();

      return response()->json([
        "message" => "records updated successfully"
      ], 200);
    } else {
      return response()->json([
        "message" => "Payment not found"
      ], 404);
    }
  }

  /**
   * Delete a payment by ID.
   */
  public function deletePayment($id)
  {
    if (Payment::where('id', $id)->exists()) {

      $payment = Payment::find($id);
      $payment->delete();

      return response()->json([
        "message" => "records deleted"
      ], 202);
    } else {
      return response()->json([
        "message" => "Payment not found"
      ], 404);
    }
  }

  /**
   * Generate PDF
   */
  public function generatePDF($id)
  {
    if (Payment::where('id', $id)->exists()) {
      $payment = Payment::where('_id', $id)->get();
      $cart = ShoppingCart::where('_id',$payment->cart_id);
      $products = Product::whereIn('_id', $cart->product_ids);
      $pdf = PDF::loadView('orderPDF', compact('payment','products'));
      return $pdf->download('order.pdf');
    } else {
      return response()->json([
        "message" => "Payment not found"
      ], 404);
    }
  }

  public function checkout(Request $request) {
       $user = User::firstOrCreate(
            [
                'email' => $request->input('email')
            ],
            [
                //'password' => Hash::make(Str::random(12)),
                'name' => $request->first_name . ' ' . $request->last_name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'user_type' => 'customer'
            ]
        );

        try {
          $payment = $user->charge(
              $request->amount,
              $request->payment_method_id
          );
          $payment = $payment->asStripePaymentIntent();
          
          $order = new Order();
          $order->user_id = $user->id;
          $order->payment_id = $request->payment_method_id;
          $order->amount = $request->amount;
          $order->orders = json_decode($request->cart,true);
          $order->save();
          return response()->json(['success' => true, 'order' => $order], 200);
        } catch (\Exception $e) {
          return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
