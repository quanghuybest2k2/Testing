<?php

use App\Http\Controllers\API\AlbumController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SubscriberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//dang ky
Route::post('register', [AuthController::class, 'register']);
// dang nhap
Route::post('login', [AuthController::class, 'login']);
// user
Route::controller(FrontendController::class)->group(function () {
    Route::get('viewHomePage', 'index');
    Route::get('getCategory', 'category');
    Route::get('fetchproducts/{slug}', 'product');
    Route::get('viewproductdetail/{category_slug}/{product_slug}', 'viewproduct');
    // Route::post('send-email', 'sendEmail');
});
// get all category from client
Route::controller(CategoryController::class)->group(function () {
    Route::get('get-all-category', 'getAllCategory');
});
// Album
Route::controller(AlbumController::class)->group(function () {
    Route::get('getAlbumPet', 'index');
    Route::post('store-albumPet', 'store');
});
// cart
Route::controller(CartController::class)->group(function () {
    Route::post('add-to-cart', 'addtocart');
    Route::get('cart', 'viewcart');
    Route::put('cart-updatequantity/{cart_id}/{scope}', 'updatequantity');
    Route::delete('delete-cartitem/{cart_id}', 'deleteCartitem');
});
// checkout
Route::controller(CheckoutController::class)->group(function () {
    Route::post('place-order', 'placeorder');
    Route::post('validate-order', 'validateOrder');
});
// Comment
Route::controller(CommentController::class)->group(function () {
    // Route::get('get-comment', 'index');
    Route::post('store-comment/{slug}', 'store');
});
// subscribers
Route::controller(SubscriberController::class)->group(function () {
    Route::get('getSubscribers', 'index');
    Route::post('subscribers', 'store');
});
// admin
Route::middleware('auth:sanctum', 'isAPIAdmin')->group(function () {

    Route::get('/checkingAuthenticated', function () {
        return response()->json(['message' => 'Bạn đã đăng nhập', 'status' => 200], 200);
    });
    // Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('view-dashboard', 'index');
    });
    // Category
    Route::controller(CategoryController::class)->group(function () {
        Route::get('view-category', 'index');
        Route::post('store-category', 'store');
        Route::get('edit-category/{id}', 'edit');
        Route::put('update-category/{id}', 'update');
        Route::delete('delete-category/{id}', 'destroy');
        Route::get('all-category', 'allcategory');
    });
    // Orders
    Route::controller(OrderController::class)->group(function () {
        Route::get('admin/orders', 'index');
        Route::get('admin/view-order/{id}', 'viewOrder');
    });
    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::post('store-product', 'store');
        Route::get('view-product', 'index');
        Route::get('edit-product/{id}', 'edit');
        Route::post('update-product/{id}', 'update');
        Route::delete('delete-product/{id}', 'destroy');
    });
    //
    // View Comment in Admin
    Route::controller(CommentController::class)->group(function () {
        Route::get('view-comment', 'index');
        Route::delete('delete-comment/{id}', 'deleteComment');
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
