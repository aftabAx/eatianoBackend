<?php



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\AuthController;

use App\Http\Controllers\RegisterController;

use App\Http\Controllers\RestaurantController;

use App\Http\Controllers\BlogController;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\wishlistController;

use App\Http\Controllers\warehouseController;

use App\Http\Controllers\membershipController;

use App\Http\Controllers\cartController;

use App\Http\Controllers\categoryController;

use App\Http\Controllers\couponController;

use App\Http\Controllers\orderController;

use App\Http\Controllers\waletController;

use App\Http\Controllers\RestaurantRatingController;

use App\Http\Controllers\ProductRatingController;

use App\Http\Controllers\ExpensesController;

use App\Http\Controllers\taxController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\DeliveryController;

use App\Http\Controllers\MomsginiController;

use App\Http\Controllers\ExpertChoice;





Route::post('/test_walet', [waletController::class,'add_data']);





//API FOR ALL LOGIN, LOGOUT, ME

Route::group([

    'middleware' => 'api',

    'prefix' => 'auth'

], function () {

    // USER AUTHENTICATION

    Route::post('signup', [AuthController::class, 'signup']);

    Route::post('login', [AuthController::class, 'login']);

    Route::post('forget_password', [AuthController::class, 'forget_password']);

    Route::post('check_otp', [AuthController::class, 'check_otp']);

    Route::post('change_password', [AuthController::class, 'change_password']);

    Route::post('update_password',[AuthController::class, 'update_password']);

// END USER AUTH



    // ADMIN AUTH

    Route::post('admin/login', [AuthController::class, 'admin_login']);

    // SUPERADMIN AUTH

    Route::post('super_admin/login', [AuthController::class, 'super_admin_login']);

    // DELIVERY AUTH

    Route::post('delivery_login', [AuthController::class, 'delivery_login']);

    



    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::get('me', [AuthController::class, 'me']);

    Route::patch('profile', [AuthController::class, 'edit_user_profile']);

    Route::get('like_blog/{id}', [BlogController::class, 'like_blog']);

    Route::get('member', [membershipController::class, 'all_membership']);

    Route::post('member', [membershipController::class, 'register_member']);

    Route::get('member/{id}', [membershipController::class, 'member_info']);

    Route::get('wishlist', [wishlistController::class, 'get_wishlist']);

    Route::post('wishlist', [wishlistController::class, 'add_wishlist']);

    Route::delete('wishlist/{id}', [wishlistController::class, 'delete_wishlist']);





    Route::post('cart', [cartController::class, 'add_cart']);

    Route::get('cart', [cartController::class, 'view_cart']);

    Route::delete('cart/{id}', [cartController::class, 'delete_cart']);

    Route::get('restaurant_cart', [cartController::class, 'view_cart_restaurant']);







    Route::post('order',[orderController::class, 'create_order']);

    Route::get('order',[orderController::class, 'all_orders']);

    Route::get('order/{id}',[orderController::class, 'get_order']);

   // route::get('order_assign',[orderController::class, 'order_assign']);

    Route::get('previous_order',[orderController::class, 'previous_order']);

    Route::get('previous_order_details',[orderController::class, 'previous_order_details']);

    Route::post('order_id', [orderController::class, 'create_order_id']);

    





    Route::post('rating', [RestaurantRatingController::class, 'restaurant_rating']);





    Route::post('product_rating', [ProductRatingController::class, 'product_rating']);







    Route::get('walet', [waletController::class,'view_balance']);

    Route::get('check_walet/{amount}', [waletController::class,'check_walet']);

    Route::post('use_walet', [waletController::class,'use_walet']);









    Route::get('payment/{merchent}',[orderController::class, 'Payment_credencial']);



    Route::get('membership_offer', [orderController::class, 'check_membership_offer']);







    Route::post('momsgini', [MomsginiController::class, 'momsgini']);







    Route::get('coupon', [couponController::class, 'all_coupon']);

    

    //user track order

    Route::get('order_status/{id}', [orderController::class, 'order_status']);



    //Route::get('referal_code', [orderController::class, 'check_referal_code']);

    // Route::post('coupon', [couponController::class, 'apply_coupon']);

    Route::post( 'payment_validation', [orderController::class, 'payment_validation'] );

});

//API FOR ALL LOGIN, LOGOUT, ME



//API FOR USER



//restaurant review

// Route::get('restaurant_review/{id}', [RestaurantReviewController::class, 'restaurant_review']);



//cart items delete on order

Route::get('order_cart_delete',[cartController::class, 'order_cart_delete']);



//order history

Route::get('order_complete',[orderController::class, 'order_complete']);

Route::get('order_upcoming',[orderController::class, 'order_upcoming']);



//tastebuds 

Route::get('tastebuds',[categoryController::class, 'tastebud']);











Route::get('all_restaurant', [RestaurantController::class, 'all_restaurant']);



Route::post('search_restaurant', [RestaurantController::class, 'search_restaurant']);



Route::post('recomanded_restaurant', [RestaurantController::class, 'recomanded_restaurant']);







Route::post('register', [RegisterController::class, 'register']);







Route::get('all_blogs', [BlogController::class, 'all_blogs']);



Route::get('like_blog/{id}', [BlogController::class, 'like_blog']);







Route::get('all_products', [ProductController::class, 'all_products']);



Route::get('all_products/{id}', [ProductController::class, 'get_product']);



Route::get('best_selling', [ProductController::class, 'best_selling']);



Route::get('restaurant_product/{id}', [ProductController::class, 'restaurant_products']);







Route::post('coupon', [couponController::class, 'apply_coupon']);





Route::get('rating/{id}', [RestaurantRatingController::class, 'all_review']);





Route::get('product_rating/{id}', [ProductRatingController::class, 'all_reviews']);





 Route::get( 'highest_rated_restaurant', [orderController::class, 'highest_rated_restaurant'] ); 



 Route::post('momsgini', [MomsginiController::class, 'momsgini']);

 

 Route::get('tastebuds_get', [categoryController::class, 'tastebuds']); 

 Route::get('all_expert', [ExpertChoice::class, 'get_expert']);

 

    Route::get('root_category', [categoryController::class, 'root_category']);

    Route::get('sub_category/{cid}', [categoryController::class, 'sub_category']);



    

    // Route::post('user_pay', [categoryController::class, 'user_pay']);



//END API FOR USER



// API FOR ADMIN



Route::group(['middleware'=>['auth:api','admin'],'prefix' => 'admin'], function () {



    Route::get('all_restaurant', [RestaurantController::class, 'admin_all_restaurant']);



    Route::post('add_restaurant', [RestaurantController::class, 'add_restaurant']);


    Route::get('edit_restaurant/{id}', [RestaurantController::class, 'get_edit_restaurant']);



    Route::patch('edit_restaurant/{id}', [RestaurantController::class, 'edit_restaurant']);

    



    Route::get('all_blogs', [BlogController::class, 'admin_all_blogs']);



    Route::post('add_blog', [BlogController::class, 'add_blog']);



    Route::get('edit_blog/{id}', [BlogController::class, 'get_edit_blog']);



    Route::patch('edit_blog/{id}', [BlogController::class, 'edit_blog']);



    Route::delete('delete_blog/{id}',[BlogController::class, 'delete_blog']);

    

    

    



    Route::get('all_products', [ProductController::class, 'admin_all_products']);



    Route::get('best_selling', [ProductController::class, 'best_selling']);



    Route::get('monthly_selling', [ProductController::class, 'monthly_selling']);

    

    

    



     Route::get('restaurant_product/{id}', [ProductController::class, 'restaurant_products']);



    Route::post('add_product', [ProductController::class, 'add_product']);



    Route::get('edit_product/{id}',[ProductController::class, 'get_product']);



    Route::patch('edit_product/{id}', [ProductController::class, 'edit_product']);









    Route::get('order',[orderController::class, 'all_orders']);

    Route::get('order/{id}',[orderController::class, 'get_order']);

    Route::get( 'all_order/{date}', [orderController::class, 'order_date'] );

    Route::post('revenue_range', [orderController::class, 'revenue_range']);

    Route::post( 'profit_range', [orderController::class, 'profit_range'] );   

    Route::get( 'highest_rated_restaurant', [orderController::class, 'highest_rated_restaurant'] );   







    Route::get('rating/{id}', [RestaurantRatingController::class, 'all_review']);



    







    Route::get('delivery', [DeliveryController::class, 'get_all_delivery']);

    Route::post('delivery', [AuthController::class, 'delivery_register']);

   Route::get('delivery/{id}', [DeliveryController::class, 'get_delivery']);

    Route::patch('delivery/{id}', [DeliveryController::class, 'edit_delivery']);

    Route::delete('delivery/{id}', [DeliveryController::class, 'delivery_delete']);

     Route::get('status/{id}', [DeliveryController::class, 'active_status']);

    Route::get('/assign_delivery_boy',[DeliveryController::class, 'assign_delivery_boy']);

    

    

        Route::post('category', [categoryController::class, 'add_category']);

    Route::get('root_category', [categoryController::class, 'root_category']);

    Route::get('sub_category/{cid}', [categoryController::class, 'sub_category']);

    // Route::get('category/{id}', [categoryController::class, 'get_category']);

    Route::post('category/{id}', [categoryController::class, 'edit_category']);

    Route::get('delete_category/{id}', [categoryController::class, 'delete_category']);



  //deliveryboy status 

  Route::get('deliveryboy_status/{id}', [DeliveryController::class, 'get_delivery']);

//order status

Route::get('order_status/{id}', [orderController::class, 'order_status']);

  //order assign 

  Route::post('order_assign',[orderController::class, 'order_assign']);

  //assign from warehouse

  Route::get('warehouse_assign',[orderController::class, 'warehouse_assign']);

  Route::get('warehouse_details/{id}',[orderController::class, 'warehouse_details']);

  Route::post('warehouse_delivery',[orderController::class, 'warehouse_delivery']);







});



   

    

//END API FOR ADMIN



//API FOR SUPER_ADMIN


Route::group(['middleware'=>['auth:api','super_admin'], 'prefix' => 'super_admin'],function () {





//Expert Choice



Route::get('expert_edit_get/{id}', [ExpertChoice::class, 'get_edit_expert']);

Route::post('expert_post', [ExpertChoice::class, 'admin_expert']);

Route::post('edit_expert_post/{id}', [ExpertChoice::class, 'edit_expert_post']);

Route::delete('delete_expert/{id}',[ExpertChoice::class, 'delete_expert']);







    Route::get('all_restaurant', [RestaurantController::class, 'admin_all_restaurant']);



    Route::post('add_restaurant', [RestaurantController::class, 'add_restaurant']);



    Route::get('edit_restaurant/{id}', [RestaurantController::class, 'get_edit_restaurant']);



    Route::patch('edit_restaurant/{id}', [RestaurantController::class, 'edit_restaurant']);









    Route::get('all_blogs', [BlogController::class, 'admin_all_blogs']);



    Route::post('add_blog', [BlogController::class, 'add_blog']);



    Route::get('edit_blog/{id}', [BlogController::class, 'get_edit_blog']);



    Route::patch('edit_blog/{id}', [BlogController::class, 'edit_blog']);



    Route::delete('delete_blog/{id}',[BlogController::class, 'delete_blog']);





    Route::get('all_products', [ProductController::class, 'admin_all_products']);



    Route::get('restaurant_product/{id}', [ProductController::class, 'restaurant_products']);



    Route::post('add_product', [ProductController::class, 'add_product']);



    Route::get('edit_product/{id}',[ProductController::class, 'admin_get_product']);



    Route::patch('edit_product/{id}', [ProductController::class, 'edit_product']);



    Route::get('best_selling', [ProductController::class, 'best_selling']);



    Route::post('monthly_selling', [ProductController::class, 'monthly_selling']);

    

    





    Route::get('members', [membershipController::class, 'view_all_member']);



    Route::get('membership', [membershipController::class, 'view_all_member_ship']);



    Route::post('membership', [membershipController::class, 'create_membership_type']);

   

    Route::patch('membership/{id}', [membershipController::class, 'edit_membership_type']); 

   

    Route::get('membership/{id}', [membershipController::class, 'get_edit_membership_type']); 

    

    Route::delete('membership/{id}',[membershipController::class, 'delete_membership_type']);

    

    



    Route::get('coupon', [couponController::class, 'all_coupon']);

    Route::post('coupon', [couponController::class, 'add_coupon']);

    Route::get('coupon/{id}', [couponController::class, 'get_coupon']);

    Route::patch('coupon/{id}', [couponController::class, 'edit_coupon']);

    Route::delete('coupon/{id}', [couponController::class, 'delete_coupon']);







    Route::get('order',[orderController::class, 'all_orders']);

    Route::get('order/{id}',[orderController::class, 'get_order']);

    //Route::post('order_assign',[orderController::class, 'order_assign']);

    Route::get( 'all_order/{date}', [orderController::class, 'order_date'] );

    Route::post('monthly_sell', [orderController::class, 'monthly_sell']);

    Route::post('revenue_range', [orderController::class, 'revenue_range']);

    Route::post( 'profit_range', [orderController::class, 'profit_range'] );   

    Route::get( 'highest_rated_restaurant', [orderController::class, 'highest_rated_restaurant'] ); 





    Route::get('warehouse/{id}', [warehouseController::class, 'get_warehouse']);

    Route::get('warehouse', [warehouseController::class, 'all_warehouse']);

 





    Route::get('rating/{id}', [RestaurantRatingController::class, 'all_review']);





    Route::get('delivery', [DeliveryController::class, 'get_all_delivery']);

    Route::post('delivery', [AuthController::class, 'delivery_register']);

    Route::get('delivery/{id}', [DeliveryController::class, 'get_delivery']);

    Route::patch('delivery/{id}', [DeliveryController::class, 'edit_delivery']);

    Route::delete('delivery/{id}', [DeliveryController::class, 'delivery_delete']);







    Route::get('admin', [AdminController::class, 'get_all_admin']);

    Route::post('admin', [AdminController::class, 'admin_register']);

    Route::delete('admin/{id}', [AdminController::class, 'admin_delete']);

 

 

 

    Route::get( 'expenses', [ExpensesController::class, 'get_expenses'] );

    Route::get( 'expenses/{id}', [ExpensesController::class, 'get_single_expense'] );

    Route::post( 'expenses', [ExpensesController::class, 'add_expenses'] );

    Route::patch( 'expenses/{id}', [ExpensesController::class, 'edit_expense'] );

    Route::delete( 'expenses/{id}', [ExpensesController::class, 'delete_expense'] );







    Route::get( 'tax', [taxController::class, 'get_tax'] );

    Route::get( 'tax/{id}', [taxController::class, 'get_single_tax'] );

    Route::post( 'tax', [taxController::class, 'add_tax'] );

    Route::patch( 'tax/{id}', [taxController::class, 'edit_tax'] );

    Route::delete( 'tax/{id}', [taxController::class, 'delete_tax'] );

    

    

    

    Route::post('category', [categoryController::class, 'add_category']);

    Route::get('root_category', [categoryController::class, 'root_category']);

    Route::get('sub_category/{cid}', [categoryController::class, 'sub_category']);

    // Route::get('category', [categoryController::class, 'get_all_category']);

    // Route::get('category/{id}', [categoryController::class, 'get_category']);

    Route::post('category/{id}', [categoryController::class, 'edit_category']);

    Route::get('delete_category/{id}', [categoryController::class, 'delete_category']);





    //tastebuds

    

    Route::post('tastebuds_post', [categoryController::class, 'tastebud_post']); 

    Route::get('edit_tastebuds/{id}', [categoryController::class, 'edit_tastebud']);

    Route::post('edit_tastebuds_post', [categoryController::class, 'edit_tastebud_post']); 

    Route::get('delete_tastebuds/{id}', [categoryController::class, 'delete_tastebud']);



});





//END API FOR SUPER_ADMIN



//API FOR DELIVERY BOY

Route::group(['middleware'=>['auth:api','delivery'], 'prefix' => 'delivery'], function () {



    Route::patch('delivery_status/{oid}', [orderController::class, 'delivery_status']);

    // Route::get('delivery_status', [orderController::class, 'delivery_status_all']);

    Route::get('delivery', [DeliveryController::class, 'view_delivery']);

    Route::get('delivery_details/{id}', [DeliveryController::class, 'delivery_details']);

    Route::post('edit_delivery', [AuthController::class, 'edit_delivery_profile']);

    Route::get('delivery_status', [DeliveryController::class, 'check_status']);

   //update delivery_status

   Route::post('edit_status', [DeliveryController::class, 'edit_delivery_status']);

});



//END API FOR DELIVERY BOY

