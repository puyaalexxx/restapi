<?php
declare(strict_types=1);

use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;

//Buyers
Route::apiResource('buyers', BuyerController::class);

//Categories
Route::apiResource('categories', CategoryController::class);

//Products
Route::apiResource('products', ProductController::class);

//Sellers
Route::apiResource('sellers', SellerController::class);

//Transactions
Route::apiResource('transactions', TransactionController::class);

//Users
Route::apiResource('users', UserController::class);

