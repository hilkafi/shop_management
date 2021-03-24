<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\HeadController;
use App\Http\Controllers\API\SubHeadController;
use App\Http\Controllers\API\PurchaseController;
use App\Http\Controllers\API\SaleController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'category' => CategoryController::class,
    'product' => ProductController::class,
    'head' => HeadController::class,
    'sub_head' => SubHeadController::class,
    'purchase' => PurchaseController::class,
    'sale' => SaleController::class,
]);

Route::get('/load_category', [CategoryController::class, 'load_category']);
Route::get('/load_head', [HeadController::class, 'load_head']);
Route::get('/load_product', [ProductController::class, 'load_product']);
Route::get('/stock', [ProductController::class, 'stock']);
Route::get('/load_stock_product', [ProductController::class, 'load_stock_product']);
Route::get('/product_stock_quantity/{id}', [ProductController::class, 'product_stock_quantity']);
Route::post('/stock/search', [ProductController::class, 'stock_search']);
Route::get('/get_purchase_rate/{id}', [ProductController::class, 'get_purchase_rate']);
Route::get('/get_sale_rate/{id}', [ProductController::class, 'get_sale_rate']);