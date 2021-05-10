<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\PaymentController;

Route::get('translation', 'Api\TransaltionController@translation');
Route::get('settings', 'Api\SettingController@index');
Route::get('product/{id}', 'Api\ProductController@getProduct');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::get('categoriesWithAllProducts', 'Api\CategoryController@getAllCategoriesWithProduct');
Route::post('checkout', [PaymentController::class,'checkout']);
Route::group(['middleware' => ['auth:api','verified']], function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['data' => $request->user()]);
    }); 
  
    /* Category */
    Route::get('categories', 'Api\CategoryController@getAllCategories');
    Route::get('category/{id}', 'Api\CategoryController@getCategory');
    Route::post('category', 'Api\CategoryController@createCategory');
    Route::post('category/save', 'Api\CategoryController@save');
    Route::put('category/{id}', 'Api\CategoryController@updateCategory');
    Route::put('category', 'Api\CategoryController@updateSequence');
    Route::delete('category/{id}', 'Api\CategoryController@deleteCategory');

    /* Product */
    Route::get('product', 'Api\ProductController@getAllProducts');
    Route::put('product', 'Api\ProductController@updateSequence');
    //Route::get('product/{id}', 'Api\ProductController@getProduct');
    Route::post('product', 'Api\ProductController@createProduct');
    Route::post('products', 'Api\ProductController@save');
    Route::put('product/{product}', 'Api\ProductController@updateProduct');
    Route::put('productCategory/{id}', 'Api\ProductController@updateProductCategory');
    Route::delete('product/{product}', 'Api\ProductController@deleteProduct');

    /* Traslation */
    Route::get('allTranslation', 'Api\TransaltionController@getAllTranslation');
    
    Route::get('getLocale', 'Api\TransaltionController@getCurrentLocale');

    /* Form Fields */
    Route::post('formField', 'Api\FormFieldsController@create');
    Route::put('formFields/{formfield}', 'Api\FormFieldsController@update');


    Route::get('formFields', 'Api\FormFieldsController@getFormByID');
    Route::get('formField/{id}', 'Api\FormFieldsController@getFormFields');
    Route::post('formFields', 'Api\FormFieldsController@createFormFields');
    Route::post('formFieldsave', 'Api\ProductController@save');
    Route::put('formFields/{id}', 'Api\FormFieldsController@updateFormFields');
    Route::put('formField', 'Api\FormFieldsController@updateSequence');
    Route::delete('formFields/{formField}', 'Api\FormFieldsController@deleteFormFields');

    /* Shopping Cart */
    Route::get('cart', 'Api\ShoppingCartController@getCartByID');
    Route::get('cart/{id}', 'Api\ShoppingCartController@getShoppingCart');
    Route::post('cart', 'Api\ShoppingCartController@createShoppingCart');
    Route::put('cart/{id}', 'Api\ShoppingCartController@updateShoppingCart');
    Route::delete('cart/{id}', 'Api\ShoppingCartController@deleteShoppingCart');

    /* Payment */
    Route::get('payment', 'Api\PaymentController@getFormByID');
    Route::get('payment/{id}', 'Api\PaymentController@getPayment');
    Route::post('payment', 'Api\PaymentController@createPayment');
    Route::put('payment/{id}', 'Api\PaymentController@updatePayment');
    Route::delete('payment/{id}', 'Api\PaymentController@deletePayment');

    /* Generate PDF */
    Route::get('generatePDf/{id}', 'Api\PaymentController@generatePDF');



    Route::post('settings', 'Api\SettingController@store');
    Route::post('settings/getUserSlug', 'Api\SettingController@getUserSlug');

    Route::post('logout', 'Api\LoginController@logout');
});
