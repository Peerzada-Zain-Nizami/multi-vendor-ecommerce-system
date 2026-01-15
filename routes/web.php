<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

use App\Http\Middleware\SellerPlanMiddleware;
/*Super Admin*/
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProdcutController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\CityMappingController as AdminCityMappingController;
use App\Http\Controllers\Admin\PlanController;
/*Sub admin*/
use App\Http\Controllers\Subadmin\SubadminController;
use App\Http\Controllers\Subadmin\SubadminSettingsController;
use App\Http\Controllers\Subadmin\SubadminWalletController;
/*Supplier*/
use App\Http\Controllers\Supplier\SupplierSettingsController;
use App\Http\Controllers\Supplier\SupplierWalletController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierProductController;
use App\Http\Controllers\Supplier\Myorder;
use App\Http\Controllers\Supplier\SupplierReportsController;
/*Seller*/
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Seller\SellerTagsController;
use App\Http\Controllers\Seller\SellerWalletController;
use App\Http\Controllers\Seller\EcomProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerSettingsController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\CityMappingController;
use App\Http\Controllers\Seller\PlanController as SellerPlan;

/*Warehouse Admin*/
use App\Http\Controllers\W_admin\WadminController;
use App\Http\Controllers\W_admin\WadminSettingsController;
use App\Http\Controllers\W_admin\WarehouseController as WadminWarehouseController;
use App\Http\Controllers\W_admin\InvoiceController as WadminInvoiceController;
use App\Http\Controllers\W_admin\StockController as WadminStockController;
use App\Http\Controllers\W_admin\PlacementController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Seller\SellerLangSetupController;
use App\Http\Controllers\W_admin\OrderManagementController as WadminOrderManagementController;

/*Smsa Shipping API*/
use App\Http\Controllers\SMSAshippingController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', [EcomProductController::class, 'test'])->name('test');
/*Smsa Shipping API*/
Route::get('/SMSA/create/pickup', [TestController::class, 'testRoute']);
Route::get('/SMSA/cancel/shipping', [SMSAshippingController::class, 'cancel_smsa_shipment']);
Route::get('/SMSA/shipping/status', [\App\Http\Controllers\W_admin\OrderManagementController::class, 'smsa_shipping_status']);
//Route::get('/test', [SMSAshippingController::class , 'smsa_cities']);
Route::get('/SMSA/shipping/get/tracking', [SMSAshippingController::class, 'smsa_shipping_tracking']);
Route::get('/', function () {
    return view('welcome');
})->name('home.page');
Route::get('lang/switch/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');
/*Super Admin*/
Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/theme', [AdminSettingsController::class, 'index'])->name('admin.theme');
    Route::get('/get_noti', [AdminSettingsController::class, 'get_noti'])->name('admin.get.noti');
    Route::post('/get_noti/see', [AdminSettingsController::class, 'get_noti_see'])->name('admin.get.noti.see');
    Route::get('/notifications', [AdminSettingsController::class, 'notification'])->name('admin.notifications');
    Route::get('/notifications/mark-all-as-read', [AdminSettingsController::class, 'notification_read'])->name('admin.notifications.read');
    Route::post('/get/languages', [AdminSettingsController::class, 'get_languages'])->name('admin.get.languages');
    #warehouse management
    Route::get('/barcode/pdf/download/{type}/{id}/{qty}', [WarehouseController::class, 'barcode_download'])->name('admin.barcode.download');
    Route::get('/warehouses-overview', [WarehouseController::class, 'overview'])->name('admin.warehouse.overview');
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('admin.warehouse');
    Route::get('/warehouses-view/{id}', [WarehouseController::class, 'view'])->name('admin.warehouse.view');
    Route::post('/warehouses-add', [WarehouseController::class, 'add_warehouse'])->name('admin.warehouse.add');
    Route::get('/warehouses-edit/{id}', [WarehouseController::class, 'edit_warehouse'])->name('admin.warehouse.edit');
    Route::post('/warehouses-update/{id}', [WarehouseController::class, 'update_warehouse'])->name('admin.warehouse.update');
    Route::get('/warehouses-delete/{id}', [WarehouseController::class, 'delete_warehouse'])->name('admin.warehouse.delete');
    Route::post('/warehouses-block-room/add/{id}', [WarehouseController::class, 'add_room_block'])->name('admin.warehouse.block.add');
    Route::post('/warehouses-rack/add/{id}', [WarehouseController::class, 'add_rack'])->name('admin.warehouse.rack.add');
    Route::get('/warehouses-show-racks/{id}', [WarehouseController::class, 'show_racks_list'])->name('admin.warehouse.racks.show');
    Route::post('/warehouses-shelf/add/{id}', [WarehouseController::class, 'add_shelf'])->name('admin.warehouse.shelf.add');

    Route::get('/warehouses-block-room/edit/{id}', [WarehouseController::class, 'edit_room_block'])->name('admin.warehouse.block.edit');
    Route::post('/warehouses-block-room/update', [WarehouseController::class, 'update_room_block'])->name('admin.warehouse.block.update');
    Route::get('/warehouses-block-room/delete/{id}', [WarehouseController::class, 'delete_room_block'])->name('admin.warehouse.block.delete');

    Route::get('/warehouses-rack/edit/{id}', [WarehouseController::class, 'edit_rack'])->name('admin.warehouse.rack.edit');
    Route::post('/warehouses-rack/update', [WarehouseController::class, 'update_rack'])->name('admin.warehouse.rack.update');
    Route::get('/warehouses-rack/delete/{id}', [WarehouseController::class, 'delete_rack'])->name('admin.warehouse.rack.delete');

    Route::get('/warehouses-shelf/edit/{id}', [WarehouseController::class, 'edit_shelf'])->name('admin.warehouse.shelf.edit');
    Route::post('/warehouses-shelf/update', [WarehouseController::class, 'update_shelf'])->name('admin.warehouse.shelf.update');
    Route::get('/warehouses-shelf/delete/{id}', [WarehouseController::class, 'delete_shelf'])->name('admin.warehouse.shelf.delete');
    #language for warehouse
    Route::post('/Warehouse_model/lang/add', [WarehouseController::class, 'warehouse_lang_add'])->name('admin.warehouse.lang.add');
    Route::get('/Warehouse_model/lang/add/edit/{id}', [WarehouseController::class, 'warehouse_lang_edit'])->name('admin.warehouse.lang.edit');
    Route::post('/Warehouse_model/lang/update', [WarehouseController::class, 'warehouse_lang_update'])->name('admin.warehouse.lang.update');
    Route::get('/Warehouse_model/lang/del/{id}', [WarehouseController::class, 'warehouse_lang_del'])->name('admin.warehouse.lang.del');
    #setup management

    #Category Management
    Route::get('/categories_list', [CategoryController::class, 'index'])->name('admin.category.list');
    Route::post('/categories_list_add', [CategoryController::class, 'create'])->name('admin.category.add');
    Route::post('/categories_list_update', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::post('/categories_list_sub', [CategoryController::class, 'add_sub'])->name('admin.category.sub');
    Route::post('/categories_list_del', [CategoryController::class, 'delete'])->name('admin.category.del');
    Route::post('/categories_list/lang/add', [CategoryController::class, 'category_lang_add'])->name('admin.category.lang.add');
    Route::get('/categories_list/lang/add/edit/{id}', [CategoryController::class, 'category_lang_edit'])->name('admin.category.lang.edit');
    Route::post('/categories_list/lang/list', [CategoryController::class, 'category_lang_list'])->name('admin.get.languages_2');
    Route::post('/categories_list/lang/update', [CategoryController::class, 'category_lang_update'])->name('admin.category.lang.update');
    Route::get('/categories_list/lang/del/{id}', [CategoryController::class, 'category_lang_del'])->name('admin.category.lang.del');
    #business model
    Route::get('/business_model', [CategoryController::class, 'business_model'])->name('admin.business.model');
    Route::post('/business_model/add', [CategoryController::class, 'business_model_add'])->name('admin.business.model.add');
    Route::get('/business_model/edit/{id}', [CategoryController::class, 'business_model_edit']);
    Route::post('/business_model/update', [CategoryController::class, 'business_model_update'])->name('admin.business.model.update');
    Route::post('/business_model/delete', [CategoryController::class, 'business_model_delete'])->name('admin.business.model.del');
    Route::post('/business_model/lang/add', [CategoryController::class, 'business_model_lang_add'])->name('admin.business.model.lang.add');
    Route::get('/business_model/lang/add/edit/{id}', [CategoryController::class, 'business_model_lang_edit'])->name('admin.business.model.lang.edit');
    Route::post('/business_model/lang/update', [CategoryController::class, 'business_model_lang_update'])->name('admin.business.model.lang.update');
    Route::get('/business_model/lang/del/{id}', [CategoryController::class, 'business_model_lang_del'])->name('admin.business.model.lang.del');
    #tax management
    Route::get('/tax-management', [TaxController::class, 'index'])->name('admin.tax.index');
    Route::post('/tax-management/add', [TaxController::class, 'add'])->name('admin.tax.add');
    Route::get('/tax-management/edit/{id}', [TaxController::class, 'edit'])->name('admin.tax.edit');
    Route::post('/tax-management/update', [TaxController::class, 'update'])->name('admin.tax.update');
    Route::post('/tax-management/delete', [TaxController::class, 'delete'])->name('admin.tax.delete');
    Route::post('/tax-management/lang/add', [TaxController::class, 'tax_lang_add'])->name('admin.tax.lang.add');
    Route::get('/tax-management/lang/add/edit/{id}', [TaxController::class, 'tax_lang_edit'])->name('admin.tax.lang.edit');
    Route::post('/tax-management/lang/update', [TaxController::class, 'tax_lang_update'])->name('admin.tax.lang.update');
    Route::get('/tax-management/lang/del/{id}', [TaxController::class, 'tax_lang_del'])->name('admin.tax.lang.del');
    #setup management end

    // #Shipping management
    // Route::get('/shipping-management',[ShippingController::class,'cancel_index'])->name('admin.shipping.index');
    // Route::post('/shipping-management/get/city/data',[ShippingController::class,'get_city_data'])->name('admin.get.city.data');
    // Route::post('/shipping-management/add',[ShippingController::class,'add_cancel'])->name('admin.cancellation.price.add');
    // Route::get('/shipping-management/edit/{id}',[ShippingController::class,'edit_cancel'])->name('admin.shipping.company.edit');
    // // Route::post('/shipping-management/update/{id}',[ShippingController::class,'update_cancel'])->name('admin.shipping.company.update');
    // Route::get('/shipping-management/delete/{id}',[ShippingController::class,'delete_cancel'])->name('admin.shipping.company.delete');

    #Plans Management
    Route::get('/seller/plan', [PlanController::class, 'index'])->name('admin.shipping.plan.index');
    Route::post('/seller/plan/add', [PlanController::class, 'add_plan'])->name('admin.seller.plan.add');
    Route::get('/seller/plan/manage', [PlanController::class, 'manage_plan'])->name('admin.seller.plan.manage');
    Route::get('/seller/plan/view/{id}', [PlanController::class, 'view_plan'])->name('admin.seller.plan.view');
    Route::get('/seller/plan/edit/{id}', [PlanController::class, 'plan_edit'])->name('admin.seller.plan.edit');
    Route::post('/seller/plan/update', [PlanController::class, 'plan_update'])->name('admin.seller.plan.update');
    Route::get('/seller/plan/delete/{id}', [PlanController::class, 'delete'])->name('admin.seller.plan.delete');
    Route::get('/seller/plan/price', [PlanController::class, 'plan_price_index'])->name('admin.seller.plan.price.index');
    Route::get('/tax-management/lang/add/edit/{id}', [TaxController::class, 'tax_lang_edit'])->name('admin.tax.lang.edit');
    Route::post('/tax-management/lang/update', [TaxController::class, 'tax_lang_update'])->name('admin.tax.lang.update');
    Route::get('/tax-management/lang/del/{id}', [TaxController::class, 'tax_lang_del'])->name('admin.tax.lang.del');

    #city Mapping
    Route::get('/download/cities/file', [AdminCityMappingController::class, 'download_city_file'])->name('admin.download.city.file.format');
    Route::get('/our/cities', [AdminCityMappingController::class, 'my_cities'])->name('admin.my.cities');
    Route::get('/cities/price', [AdminCityMappingController::class, 'cities_prices'])->name('admin.cities.prices');
    Route::post('/group_add', [AdminCityMappingController::class, 'add_group'])->name('admin.city.group.add');
    Route::post('/group_price_add', [AdminCityMappingController::class, 'add_group_price'])->name('admin.city.group.price.add');
    Route::get('/group_price_add/edit/{id}', [AdminCityMappingController::class, 'edit'])->name('admin.city.group.price.edit');
    Route::post('/shipping-management/updates', [AdminCityMappingController::class, 'update'])->name('admin.shipping.company.update');
    Route::get('/shipping-management/delete/{id}', [AdminCityMappingController::class, 'delete'])->name('admin.shipping.company.delete');
    Route::post('/group_cities_add', [AdminCityMappingController::class, 'add_cities'])->name('admin.group.cities.add');
    Route::post('/get/group/cities', [AdminCityMappingController::class, 'get_group_cities'])->name('admin.get.group.cities');
    Route::post('/delete/group/cities', [AdminCityMappingController::class, 'delete_group_cities'])->name('admin.delete.group.cities');
    Route::get('/import/city', [AdminCityMappingController::class, 'import_city'])->name('admin.import.city');
    Route::post('/add/city/file', [AdminCityMappingController::class, 'add_city_file'])->name('admin.add.city.file');
    Route::get('/city/mapping', [AdminCityMappingController::class, 'city_mapping'])->name('admin.city.mapping');
    Route::get('/seller/cities', [AdminCityMappingController::class, 'seller_cities'])->name('admin.seller.city');
    Route::get('/seller/cities/view/{id}', [AdminCityMappingController::class, 'seller_cities_view'])->name('admin.seller.city.view');

    #Product Management
    Route::get('/add_product', [ProdcutController::class, 'index'])->name('admin.product');
    Route::post('/add_product', [ProdcutController::class, 'create'])->name('admin.product.add');
    Route::get('/manage_product', [ProdcutController::class, 'show'])->name('admin.product.manage');
    Route::get('/manage_product/view/{id}', [ProdcutController::class, 'view']);
    Route::get('/manage_product/edit/{id}', [ProdcutController::class, 'edit']);
    Route::post('/update_product', [ProdcutController::class, 'update'])->name('admin.product.update');
    Route::post('/manage_product/image/delete', [ProdcutController::class, 'image_delete'])->name('admin.product.image.delete');
    Route::get('/catalog', [ProdcutController::class, 'catalog'])->name('admin.product.catalog');
    Route::get('/catalog/view/{id}', [ProdcutController::class, 'catalog_view'])->name('admin.product.catalog.view');
    #language for product
    Route::post('/Product_model/lang/add', [ProdcutController::class, 'product_lang_add'])->name('admin.product.lang.add');
    Route::get('/Product_model/lang/add/edit/{id}', [ProdcutController::class, 'product_lang_edit'])->name('admin.product.lang.add.edit');
    Route::post('/Product_model/lang/update/{id}', [ProdcutController::class, 'product_lang_update'])->name('admin.product.lang.update');
    Route::get('/Product_model/lang/del/{id}', [ProdcutController::class, 'product_lang_del'])->name('admin.product.lang.del');

    #Invoice Management
    Route::get('/new_invoice', [InvoiceController::class, 'new_invoice'])->name('admin.invoice.new');
    Route::post('/new_invoice', [InvoiceController::class, 'show_supplier'])->name('admin.invoice.show.list');
    Route::post('/new_invoice/add', [InvoiceController::class, 'add_list'])->name('admin.invoice.add.list');
    Route::post('/new_invoice/add/check', [InvoiceController::class, 'list_check'])->name('admin.invoice.add.list.check');
    Route::get('/invoice_checkout', [InvoiceController::class, 'invoice_checkout'])->name('admin.invoice.checkout');
    Route::get('/invoice_checkout/view/{id}', [InvoiceController::class, 'invoice_checkout_view'])->name('admin.invoice.checkout.view');
    Route::post('/invoice_checkout/view/', [InvoiceController::class, 'invoice_checkout_get'])->name('admin.invoice.checkout.get');
    Route::post('/invoice_checkout/pro_del', [InvoiceController::class, 'checkout_pro_del'])->name('admin.invoice.checkout.product.del');
    Route::post('/invoice_checkout/pro_plus', [InvoiceController::class, 'checkout_pro_plus'])->name('admin.invoice.checkout.product.plus');
    Route::post('/invoice_checkout/pro_minus', [InvoiceController::class, 'checkout_pro_minus'])->name('admin.invoice.checkout.product.minus');
    Route::get('/invoice_checkout/sent_order/{id}', [InvoiceController::class, 'create_order'])->name('admin.invoice.order');
    Route::get('/manage_invoice', [InvoiceController::class, 'manage_invoice'])->name('admin.manage.invoice');
    Route::get('/manage_invoice/invoice/{id}', [InvoiceController::class, 'invoice_view'])->name('admin.view.invoice');
    Route::post('/manage_invoice/invoice/status/{id}', [InvoiceController::class, 'invoice_status'])->name('admin.invoice.status');
    Route::post('/manage_invoice/invoice/payment', [InvoiceController::class, 'invoice_pay'])->name('admin.invoice.pay');
    Route::get('/return_invoices/', [InvoiceController::class, 'invoice_return'])->name('admin.manage.invoice.return');
    Route::get('/return_invoices/partial/{id}', [InvoiceController::class, 'invoice_return_partial'])->name('admin.manage.invoice.return.partial');
    Route::get('/return_invoices/full/{id}', [InvoiceController::class, 'invoice_return_full'])->name('admin.manage.invoice.return.full');
    Route::post('/return_invoices/partial_request/{id}', [InvoiceController::class, 'partial_return'])->name('admin.manage.invoice.return.partial.request');
    Route::get('/return_invoices/full_request/{id}', [InvoiceController::class, 'full_return'])->name('admin.manage.invoice.return.full.request');
    Route::get('/return_invoices/invoice/{id}', [InvoiceController::class, 'return_view'])->name('admin.view.invoice.return');
    Route::post('/return_invoices/invoice/status/{id}', [InvoiceController::class, 'invoice_return_status'])->name('admin.invoice.return.status');
    Route::post('/return_invoice/invoice/pay', [InvoiceController::class, 'invoice_return_pay'])->name('admin.invoice.return.pay');
    #stock Management
    Route::get('/stock_catalog', [StockController::class, 'view'])->name('stock.catalog');
    Route::post('/stock_catalog/edit', [StockController::class, 'edit'])->name('stock.catalog.edit');
    Route::post('/stock_catalog/update', [StockController::class, 'update'])->name('stock.catalog.update');
    Route::get('/stock_catalog/check-all-cities-selected', [StockController::class, 'checkAllCitiesSelected'])->name('stock.check.all.cities.selected');
    Route::get('/stock_details', [StockController::class, 'stock_details'])->name('admin.stock.details');
    Route::post('/stock_details/filter', [StockController::class, 'stock_details_filter'])->name('admin.details.filter');

    #Orders
    Route::get('/woocommerce/new/orders', [OrderManagementController::class, 'new_orders'])->name('admin.woo.order.list');
    Route::get('/woocommerce/refunded/orders', [OrderManagementController::class, 'refunded_orders'])->name('admin.woo.refunded.order');
    Route::get('/woocommerce/orders/management', [OrderManagementController::class, 'orders_management'])->name('admin.woo.order.management');
    Route::post('/settings/api-integration/on-off', [OrderManagementController::class, 'on_off'])->name('admin.order.auto.processing.on_off');

    Route::get('/order/invoice/checkout/view/{id}', [OrderManagementController::class, 'invoice_checkout_view'])->name('admin.order.invoice.checkout.view');
    Route::post('/order/approved{id}', [OrderManagementController::class, 'order_approved'])->name('admin.order.approved');
    Route::post('/manage_invoice/invoice/pay/refund/cancelorder', [OrderManagementController::class, 'refund_cancelorder'])->name('admin.order.refund.cancelorder');
    Route::post('/manage_invoice/invoice/pay', [OrderManagementController::class, 'invoice_pay'])->name('admin.order.pay');
    Route::post('/manage_invoice/invoice/pay/refund/return', [OrderManagementController::class, 'refund_return_pay'])->name('admin.order.pay.refund.return');
    Route::post('/manage_invoice/invoice/pay/refund/return/WO/Fee', [OrderManagementController::class, 'refund_return_pay_3'])->name('admin.order.pay.refund.return.3');
    Route::get('/woocommerce/refunded/orders/index', [OrderManagementController::class, 'woo_orders_refunded_index'])->name('admin.woo.refunded.order.list');
    Route::get('/order/tracking/{id}', [OrderManagementController::class, 'order_tracking'])->name('admin.order.tracking');
    Route::get('/refunded/order/view/{id}', [OrderManagementController::class, 'refunded_order_view'])->name('admin.refunded.order.view');
    Route::post('/order/status/send/{id}', [OrderManagementController::class, 'order_status'])->name('admin.order.status.send');
    Route::post('/refund/order/status/send/{id}', [OrderManagementController::class, 'refund_order_status'])->name('admin.refund.order.status.send');
    Route::post('send/order/invoice/{id}', [OrderManagementController::class, 'send_order'])->name('admin.send.order.to.w_admin');
    Route::get('send/order/status/invoice/{id}', [OrderManagementController::class, 'send_order_status'])->name('admin.send.order.status.to.w_admin');
    Route::get('/return/request/to/shipping_company/{id}', [OrderManagementController::class, 'return_request_to_shipping_company'])->name('admin.return.request.to.shipping.company');

    #Reports
    Route::get('/invoice_reports', [ReportsController::class, 'invoice_report'])->name('admin.invoice.reports');
    Route::post('/invoice_reports/filter-data', [ReportsController::class, 'invoice_report_filter'])->name('admin.invoice.reports.filter');
    Route::get('/return_reports', [ReportsController::class, 'return_report'])->name('admin.return.reports');
    Route::post('/return_reports/filter-data', [ReportsController::class, 'return_report_filter'])->name('admin.return.reports.filter');
    #User Management
    Route::get('/users', [AdminController::class, 'usersindex'])->name('admin.users');
    Route::get('/user/subadmin', [AdminController::class, 'subindex'])->name('admin.subadmin');
    Route::post('/user/subadmin/add', [AdminController::class, 'Addsubadmin'])->name('admin.subadmin.add');
    Route::get('/user/warehouse-admin', [AdminController::class, 'Wadminindex'])->name('admin.Wadmin');
    Route::post('/user/warehouse-admin/add', [AdminController::class, 'add_Wadmin'])->name('admin.Wadmin.add');
    Route::get('/user/view/{id}', [AdminController::class, 'user_view'])->name('admin.user.view');
    Route::get('/user/supplier', [AdminController::class, 'supplierindex'])->name('admin.supplier');
    Route::post('/user/supplier/add', [AdminController::class, 'addsupplier'])->name('admin.supplier.add');
    Route::get('/user/seller', [AdminController::class, 'sellerindex'])->name('admin.seller');
    Route::post('/user/seller/add', [AdminController::class, 'addseller'])->name('admin.seller.add');
    Route::get('/users/wallets', [AdminController::class, 'users_wallets'])->name('admin.users.wallets');
    Route::get('/users/wallets/transactions', [AdminController::class, 'users_transaction'])->name('admin.users.transactions');
    Route::get('/users/wallets/transactions/view/{id}', [AdminController::class, 'view_user_trans'])->name('admin.users.transactions.view');
    Route::post('/users/wallets/request/reject', [AdminController::class, 'reject_payment_request'])->name('admin.users.request.reject');
    Route::get('/users/wallets/balance/approve/{id}', [AdminController::class, 'approve_user_balance'])->name('admin.users.balance.approved');
    #wallet Management
    Route::get('/mywallet', [AdminWalletController::class, 'mywallet'])->name('admin.wallet');
    Route::get('/wallet/transactions', [AdminWalletController::class, 'trans'])->name('admin.transhistory');
    Route::get('/wallet/transactions/view/{id}', [AdminWalletController::class, 'view_trans'])->name('admin.view.trans');
    Route::post('/wallet/deposit', [AdminWalletController::class, 'add_deposit'])->name('admin.deposit');
    Route::post('/wallet/transfar', [AdminWalletController::class, 'add_transfar'])->name('admin.transfar');
    Route::post('/wallet/withdraw', [AdminWalletController::class, 'add_withdraw'])->name('admin.withdraw');
    #settings
    Route::get('/settings/profile', [AdminSettingsController::class, 'profile'])->name('admin.profile');
    Route::post('/settings/profile', [AdminSettingsController::class, 'profile_update'])->name('admin.profile.update');
    Route::post('/settings/profile/image', [AdminSettingsController::class, 'profile_image'])->name('admin.profile.image');
    Route::post('/settings/profile/password', [AdminSettingsController::class, 'profile_password'])->name('admin.profile.password');
    Route::get('/settings/smsa-credentials/', [AdminSettingsController::class, 'smsa_credentials'])->name('admin.smsa.credentials');
    Route::post('/settings/store-credentials/', [AdminSettingsController::class, 'store_credentials'])->name('admin.store.credentials');
    Route::get('/settings/edit-credentials/{id}', [AdminSettingsController::class, 'edit_credentials'])->name('admin.edit.credentials');
    Route::post('/settings/update-credentials', [AdminSettingsController::class, 'update_credentials'])->name('admin.update.credentials');



    //    #Woocommerce Setup
    //    #Tax
    //    Route::get('/woocommerce-setup',[WoocommerceSetupController::class,'index'])->name('admin.woocommerce.setup');
    //    Route::post('/add/tax/class',[WoocommerceSetupController::class,'add_tax_class'])->name('admin.add.tax.class');
    //    Route::get('/del/tax/class/{id}',[WoocommerceSetupController::class,'delete_tax_class'])->name('admin.woo.tax.delete');
    //    #shipping class
    //    Route::post('/add/shipping/class',[WoocommerceSetupController::class,'add_shipping_class'])->name('admin.add.shipping.class');
    //    Route::get('/del/shipping/class/{id}',[WoocommerceSetupController::class,'delete_shipping_class'])->name('admin.woo.shipping.delete');
    //
    //    #shipping zone and region
    //    Route::post('/add/shipping/zone',[WoocommerceSetupController::class,'add_shipping_zone'])->name('admin.add.shipping.zone');
    //    Route::get('/del/shipping/zone/{id}',[WoocommerceSetupController::class,'delete_shipping_zone'])->name('admin.woo.shipping.zone.delete');
    //
    //
    //    #shipping Methodseller.send.order.invoice
    //    Route::post('/add/shipping/zone/method',[WoocommerceSetupController::class,'add_shipping_zone_method'])->name('admin.add.shipping.zone.method');
    //    Route::get('/del/shipping/zone/method/{id}',[WoocommerceSetupController::class,'delete_shipping_zone_method'])->name('admin.woo.shipping.zone.method.delete');
    //
    //    #shipping cost
    //    Route::post('/add/shipping/cost',[WoocommerceSetupController::class,'add_shipping_cost'])->name('admin.add.shipping.cost');
    //    Route::get('/del/shipping/cost/{id}',[WoocommerceSetupController::class,'delete_shipping_cost'])->name('admin.woo.shipping.cost.delete');
});

/*Sub Admin*/
Route::middleware(['auth', 'subadmin', 'verified'])->prefix('subadmin')->group(function () {
    Route::get('/', [SubadminController::class, 'index'])->name('subadmin.dashboard');
    Route::post('/theme', [SubadminSettingsController::class, 'index'])->name('subadmin.theme');

    #wallet Section

    Route::get('/mywallet', [SubadminWalletController::class, 'mywallet'])->name('subadmin.wallet');
    Route::get('/wallet/transactions', [SubadminWalletController::class, 'trans'])->name('subadmin.transhistory');
    Route::get('/wallet/transactions/view/{id}', [SubadminWalletController::class, 'view_trans'])->name('subadmin.view.trans');
    Route::post('/wallet/deposit', [SubadminWalletController::class, 'add_deposit'])->name('subadmin.deposit');
    Route::post('/wallet/transfar', [SubadminWalletController::class, 'add_transfar'])->name('subadmin.transfar');
    Route::post('/wallet/withdraw', [SubadminWalletController::class, 'add_withdraw'])->name('subadmin.withdraw');
    Route::get('/paytabs_response', [SubadminWalletController::class, 'paytabs_response']);
    Route::get('/paypal_response', [SubadminWalletController::class, 'paypal_response'])->name('subadmin.paypal.response');

    Route::get('/banks', [SubadminWalletController::class, 'bank_list'])->name('subadmin.banks.list');
    Route::get('/paypal', [SubadminWalletController::class, 'paypal_list'])->name('subadmin.paypal.list');

    #settings
    Route::get('/settings/bank', [SubadminSettingsController::class, 'bank'])->name('subadmin.bank');
    Route::get('/settings/bank', [SubadminSettingsController::class, 'bank'])->name('subadmin.bank');
    Route::get('/settings/paypal', [SubadminSettingsController::class, 'paypal'])->name('subadmin.paypal');
    Route::get('/settings/profile', [SubadminSettingsController::class, 'profile'])->name('subadmin.profile');


    Route::post('/settings/bank', [SubadminSettingsController::class, 'bank_add'])->name('subadmin.bank.add');
    Route::post('/settings/bank/model', [SubadminSettingsController::class, 'bank_add_model'])->name('subadmin.bank.add.model');
    Route::post('/settings/paypal/model', [SubadminSettingsController::class, 'paypal_add_model'])->name('subadmin.paypal.add.model');
    Route::post('/settings/paypal', [SubadminSettingsController::class, 'paypal_add'])->name('subadmin.paypal.add');
    Route::post('/settings/profile', [SubadminSettingsController::class, 'profile_update'])->name('subadmin.profile.update');
    Route::post('/settings/profile/image', [SubadminSettingsController::class, 'profile_image'])->name('subadmin.profile.image');
    Route::post('/setting/pofile/password', [SubadminSettingsController::class, 'profile_password'])->name('subadmin.profile.password');
    Route::post('/settings/bank/delete', [SubadminSettingsController::class, 'bank_delete'])->name('subadmin.bank.del');
    Route::post('/settings/paypal/delete', [SubadminSettingsController::class, 'paypal_delete'])->name('subadmin.paypal.del');
});

/*Supplier*/
Route::middleware(['auth', 'supplier', 'verified'])->prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.dashboard');
    Route::post('/theme', [SupplierSettingsController::class, 'index'])->name('supplier.theme');
    Route::get('/get_noti', [SupplierSettingsController::class, 'get_noti'])->name('supplier.get.noti');
    Route::post('/get_noti/see', [SupplierSettingsController::class, 'get_noti_see'])->name('supplier.get.noti.see');
    Route::get('/notifications', [SupplierSettingsController::class, 'notification'])->name('supplier.notifications');
    Route::get('/notifications/mark-all-as-read', [SupplierSettingsController::class, 'notification_read'])->name('supplier.notifications.read');
    #Product Management
    Route::get('/company_catalog', [SupplierProductController::class, 'index'])->name('supplier.company.catalog');
    Route::post('/company_catalog/filter-data', [SupplierProductController::class, 'index_filter'])->name('supplier.company.catalog.filter');
    Route::get('/company_catalog/view/{id}', [SupplierProductController::class, 'view']);
    Route::post('/company_catalog/add_list', [SupplierProductController::class, 'add_list'])->name('supplier.company.catalog.addlist');
    Route::get('/mycatalog', [SupplierProductController::class, 'view_catalog'])->name('supplier.catalog');
    Route::get('/mycatalog/edit/{id}', [SupplierProductController::class, 'show'])->name('supplier.catalog.edit');
    Route::post('/mycatalog/update', [SupplierProductController::class, 'update'])->name('supplier.catalog.update');
    Route::get('/mycatalog/delete/{id}', [SupplierProductController::class, 'delete'])->name('supplier.catalog.delete');
    #Orders Management
    Route::get('/my_orders', [Myorder::class, 'index'])->name('supplier.myorder');
    Route::get('/my_orders/{id}', [Myorder::class, 'order_view'])->name('supplier.myorder.view');
    Route::post('/my_orders/status/{id}', [Myorder::class, 'invoice_status'])->name('supplier.invoice.status');
    Route::get('/return_request', [Myorder::class, 'mangage_return'])->name('supplier.return');
    Route::get('/return_view/{id}', [Myorder::class, 'return_view'])->name('supplier.return.view');
    Route::post('/return_view/status/{id}', [Myorder::class, 'invoice_return_status'])->name('supplier.invoice.return.status');
    #Reports
    Route::get('/sale_reports', [SupplierReportsController::class, 'sale_report'])->name('supplier.sale.reports');
    Route::post('/sale_reports/filter-data', [SupplierReportsController::class, 'sale_report_filter'])->name('supplier.sale.reports.filter');
    Route::get('/return_reports', [SupplierReportsController::class, 'return_report'])->name('supplier.return.reports');
    Route::post('/return_reports/filter-data', [SupplierReportsController::class, 'return_report_filter'])->name('supplier.return.reports.filter');
    #wallet Section
    Route::get('/mywallet', [SupplierWalletController::class, 'mywallet'])->name('supplier.wallet');
    Route::get('/wallet/transactions', [SupplierWalletController::class, 'trans'])->name('supplier.transhistory');
    Route::get('/wallet/transactions/view/{id}', [SupplierWalletController::class, 'view_trans'])->name('supplier.view.trans');
    #settings
    Route::get('/settings/profile', [SupplierSettingsController::class, 'profile'])->name('supplier.profile');
    Route::post('/settings/profile', [SupplierSettingsController::class, 'profile_update'])->name('supplier.profile.update');
    Route::post('/settings/profile/image', [SupplierSettingsController::class, 'profile_image'])->name('supplier.profile.image');
    Route::post('/settings/Profile/password', [SupplierSettingsController::class, 'profile_password'])->name('supplier.profile.password');
});

/*Seller*/
Route::middleware(['auth', 'seller', 'verified'])->prefix('seller')->group(function () {
    #if middleware has false
    Route::get('/plan/not/subscribe', [SellerPlan::class, 'PlanNotSubscribe'])->name('seller.plan.not.subscribe.index');

    #Seller Plane
    Route::get('/plan', [SellerPlan::class, 'index'])->name('seller.plan.index');
    Route::get('/plan/get/{id}', [SellerPlan::class, 'plan_get'])->name('seller.plan.get');
    Route::get('/current/plan', [SellerPlan::class, 'current_plan'])->name('seller.current.plan');
    Route::get('/plan/subscribe/{id}', [SellerPlan::class, 'subscribe'])->name('seller.plan.subscribe');
    Route::post('/plan/subscribe/type', [SellerPlan::class, 'subscribe_type'])->name('seller.plan.subscribe.with.type');
    Route::get('/plan/unsubscribe/{id}', [SellerPlan::class, 'unsubscribe'])->name('seller.plan.unsubscribe');
    Route::get('/plan/view/{id}', [SellerPlan::class, 'view'])->name('seller.plan.view');
    Route::get('/plan/price', [SellerPlan::class, 'plan_price_index'])->name('seller.plan.price.index');

    #deposit amount in his wallet
    Route::get('/mywallet', [SellerWalletController::class, 'mywallet'])->name('seller.wallet');
    Route::get('/wallet/transactions', [SellerWalletController::class, 'trans'])->name('seller.transhistory');
    Route::get('/wallet/transactions/view/{id}', [SellerWalletController::class, 'view_trans'])->name('seller.view.trans');
    Route::post('/wallet/transactions/get', [SellerWalletController::class, 'trans_get'])->name('seller.trans.get');
    Route::post('/decrypt/value', [SellerWalletController::class, 'decrypt_value'])->name('seller.decrypt.value');
    Route::post('/wallet/deposit', [SellerWalletController::class, 'add_deposit'])->name('seller.deposit');
    Route::post('/wallet/deposit/update', [SellerWalletController::class, 'update_deposit'])->name('seller.deposit.update');

    #for subscribe plan or not Middleware
    Route::middleware([SellerPlanMiddleware::class])->group(function () {
        Route::get('/', [SellerController::class, 'index'])->name('seller.dashboard');
        Route::post('/theme', [SellerSettingsController::class, 'index'])->name('seller.theme');
        Route::get('/get_noti', [SellerSettingsController::class, 'get_noti'])->name('seller.get.noti');
        Route::post('/get_noti/see', [SellerSettingsController::class, 'get_noti_see'])->name('seller.get.noti.see');
        Route::get('/get_noti/see/{id}', [SellerSettingsController::class, 'get_noti_see'])->name('seller.get.noti.see.id');
        Route::get('/notifications', [SellerSettingsController::class, 'notification'])->name('seller.notifications');
        Route::get('/notifications/mark-all-as-read', [SellerSettingsController::class, 'notification_read'])->name('seller.notifications.read');
        Route::post('/get/languages', [SellerSettingsController::class, 'get_languages'])->name('seller.get.languages');

        #company catalog
        Route::get('/company_catalog', [SellerProductController::class, 'index'])->name('seller.company.catalog');
        Route::get('/company_catalog/check-all-cities-selected', [SellerProductController::class, 'checkAllCitiesSelected'])->name('seller.check.all.cities.selected');
        Route::post('/company_catalog/filter-data', [SellerProductController::class, 'index_filter'])->name('seller.company.catalog.filter');
        Route::get('/company_catalog/view/{id}', [SellerProductController::class, 'view'])->name('seller.company.catalog.view');
        Route::post('/company_catalog/add_list', [SellerProductController::class, 'add_list'])->name('seller.company.catalog.add_list');
        #Drop shipping
        Route::get('/drop_shipping_catalog', [SellerProductController::class, 'drop_catalog'])->name('seller.drop.catalog');
        Route::get('/drop_shipping_catalog/edit/{id}', [SellerProductController::class, 'drop_catalog_edit'])->name('seller.drop.catalog.edit');
        Route::post('/drop_shipping_catalog/update', [SellerProductController::class, 'drop_catalog_update'])->name('seller.drop.catalog.update');
        Route::get('/drop_shipping_catalog/delete/{id}', [SellerProductController::class, 'drop_catalog_delete'])->name('seller.drop.catalog.delete');
        Route::post('/drop_shipping_catalog/img/delete', [SellerProductController::class, 'drop_catalog_delete_img'])->name('seller.drop.catalog.delete.img');
        #Category Management
        Route::get('/categories_list', [SellerCategoryController::class, 'index'])->name('seller.category.list');
        Route::post('/categories_list_add', [SellerCategoryController::class, 'create'])->name('seller.category.add');
        Route::post('/categories_list_update', [SellerCategoryController::class, 'update'])->name('seller.category.update');
        Route::post('/categories_list_sub', [SellerCategoryController::class, 'add_sub'])->name('seller.category.sub');
        Route::post('/categories_list_del', [SellerCategoryController::class, 'delete'])->name('seller.category.del');
        Route::post('/categories_list/lang/list', [SellerCategoryController::class, 'category_lang_list'])->name('seller.get.languages_2');
        Route::post('/categories_list/lang/add', [SellerCategoryController::class, 'category_lang_add'])->name('seller.category.lang.add');
        Route::get('/categories_list/lang/add/edit/{id}', [SellerCategoryController::class, 'category_lang_edit'])->name('seller.category.lang.edit');
        Route::post('/categories_list/lang/update', [SellerCategoryController::class, 'category_lang_update'])->name('seller.category.lang.update');
        Route::get('/categories_list/lang/del/{id}', [SellerCategoryController::class, 'category_lang_del'])->name('seller.category.lang.del');

        #Tags Management
        Route::get('/tags-list', [SellerTagsController::class, 'tags_list'])->name('seller.tags.list');
        Route::post('/tag/add', [SellerTagsController::class, 'tag_add'])->name('seller.tag.add');
        Route::get('/tags-list/edit/{id}', [SellerTagsController::class, 'tag_edit']);
        Route::post('/tag/update', [SellerTagsController::class, 'tag_update'])->name('seller.tag.update');
        Route::post('/tag/delete', [SellerTagsController::class, 'tag_delete'])->name('seller.tag.del');

        Route::post('/tag/lang/list', [SellerTagsController::class, 'get_languages'])->name('seller.tag.get.languages');
        Route::post('/tag/lang/add', [SellerTagsController::class, 'tag_lang_add'])->name('seller.tag.lang.add');
        Route::get('/tag/lang/add/edit/{id}', [SellerTagsController::class, 'tag_lang_edit'])->name('seller.tag.lang.edit');
        Route::post('/tag/lang/update', [SellerTagsController::class, 'tag_lang_update'])->name('seller.tag.lang.update');
        Route::get('/tag/lang/del/{id}', [SellerTagsController::class, 'tag_lang_del'])->name('seller.tag.lang.del');

        #Listing
        Route::post('/woocommerce_listing', [EcomProductController::class, 'woo_add'])->name('seller.woo.add');
        Route::post('/shopify_listing', [EcomProductController::class, 'shopify_add'])->name('seller.shopify.add');

        #Orders
        Route::post('/order/shipping_from_us', [OrderController::class, 'on_off'])->name('seller.order.shipping.from.us');
        Route::post('/order/auto/payment', [OrderController::class, 'payment_on_off'])->name('seller.order.auto.payment');
        Route::get('/woocommerce/orders/index', [OrderController::class, 'woo_orders_index'])->name('seller.woo.order.list');
        Route::get('/woocommerce/orders/management', [OrderController::class, 'woo_orders_management'])->name('seller.woo.order.management');
        Route::get('/invoice/checkout/view/{id}', [OrderController::class, 'invoice_checkout_view'])->name('seller.invoice.checkout.view');
        Route::get('/invoice/refund/notification/{id}', [OrderController::class, 'refund_notification'])->name('seller.invoice.refund.notificaion');
        /* cancel/confirm order depend on stock */
        Route::get('/invoice/confirm/{id}', [OrderController::class, 'invoice_confirm'])->name('seller.invoice.confirm');
        Route::get('/invoice/cancel/{id}', [OrderController::class, 'invoice_cancel'])->name('seller.invoice.cancel');

        Route::get('/woocommerce/refunded/orders', [OrderController::class, 'refunded_orders'])->name('seller.woo.refunded.order');
        Route::get('/woocommerce/refunded/orders/view/{id}', [OrderController::class, 'refunded_orders_view'])->name('seller.woo.refunded.order.view');
        Route::post('/manage_invoice/invoice/pay', [OrderController::class, 'invoice_pay'])->name('seller.invoice.pay');
        Route::get('/order/tracking/{id}', [OrderController::class, 'order_tracking'])->name('seller.order.tracking');
        Route::get('/woocommerce/orders/splits/{order_id}/{woo_product_id}', [OrderController::class, 'split_order'])->name('seller.split.order');
        Route::get('/invoice/checkout', [OrderController::class, 'invoice_checkout'])->name('seller.invoice.checkout');
        Route::post('/invoice/shipping/get', [OrderController::class, 'shipping_get'])->name('seller.invoice.shipping.get');
        Route::get('/generate/shipping/waybill/{id}', [OrderController::class, 'generate_shipment_waybill'])->name('Seller.generate.shipping.waybill');
        Route::get('send/order/invoice/{id}', [OrderController::class, 'send_order'])->name('seller.send.order.invoice');
        Route::post('send/refund/order/{id}', [OrderController::class, 'send_refund_order'])->name('seller.send.refund.status.to.admin');

        #city Mapping
        Route::get('/our/city', [CityMappingController::class, 'our_city'])->name('seller.our.city');
        Route::post('/get/city', [CityMappingController::class, 'get_city'])->name('seller.city.get');
        Route::post('/city/add/', [CityMappingController::class, 'city_add'])->name('seller.city.add');
        Route::get('/admin/cities', [CityMappingController::class, 'admin_cities'])->name('seller.admin.city');
        Route::get('/city/mapping', [CityMappingController::class, 'Mapping_view'])->name('seller.city.mapping.view');
        Route::get('/download/cities/file', [CityMappingController::class, 'download_city_file'])->name('seller.download.city.file.format');
        Route::post('/add/city/file', [CityMappingController::class, 'add_city_file'])->name('seller.add.city.file');


        #wallet Section
        Route::post('/wallet/transfar', [SellerWalletController::class, 'add_transfar'])->name('seller.transfar');
        Route::post('/wallet/withdraw', [SellerWalletController::class, 'add_withdraw'])->name('seller.withdraw');
        Route::get('/paytabs_response', [SellerWalletController::class, 'paytabs_response'])->name('seller.paytabs.response');
        Route::get('/paypal_response', [SellerWalletController::class, 'paypal_response'])->name('seller.paypal.response');

        Route::get('/banks', [SellerWalletController::class, 'bank_list'])->name('seller.banks.list');
        Route::get('/paypal', [SellerWalletController::class, 'paypal_list'])->name('seller.paypal.list');

        #settings
        Route::get('/settings/api-integration', [SellerSettingsController::class, 'api_integration'])->name('seller.api.view');
        Route::post('/settings/api-integration/woo', [SellerSettingsController::class, 'woo_integrate'])->name('seller.api.woo');
        Route::post('/settings/api-integration/shopify', [SellerSettingsController::class, 'shopify_integrate'])->name('seller.api.shopify');
        Route::get('/settings/api-integration/delete/{type}', [SellerSettingsController::class, 'api_delete'])->name('seller.api.delete');
        Route::post('/settings/api-integration/on-off', [SellerSettingsController::class, 'on_off'])->name('seller.api.on_off');
        Route::get('/settings/smsa-credentials/', [SellerSettingsController::class, 'smsa_credentials'])->name('seller.smsa.credentials');
        Route::post('/settings/store-credentials/', [SellerSettingsController::class, 'store_credentials'])->name('seller.store.credentials');
        Route::get('/settings/edit-credentials/{id}', [SellerSettingsController::class, 'edit_credentials'])->name('seller.edit.credentials');
        Route::post('/settings/update-credentials', [SellerSettingsController::class, 'update_credentials'])->name('seller.update.credentials');
        Route::get('/settings/bank', [SellerSettingsController::class, 'bank'])->name('seller.bank');
        Route::get('/settings/paypal', [SellerSettingsController::class, 'paypal'])->name('seller.paypal');
        Route::get('/settings/profile', [SellerSettingsController::class, 'profile'])->name('seller.profile');
        #Language Setup
        Route::get('/settings/language/setup', [SellerLangSetupController::class, 'language_setup_view'])->name('seller.language.setup');
        Route::post('/settings/language/setup/add', [SellerLangSetupController::class, 'lang_setup_add'])->name('seller.lang.setup.add');
        Route::post('/settings/language/setup/update/{id}', [SellerLangSetupController::class, 'lang_setup_update'])->name('seller.lang.setup.update');

        Route::post('/settings/bank', [SellerSettingsController::class, 'bank_add'])->name('seller.bank.add');
        Route::post('/settings/bank/model', [SellerSettingsController::class, 'bank_add_model'])->name('seller.bank.add.model');
        Route::post('/settings/paypal/model', [SellerSettingsController::class, 'paypal_add_model'])->name('seller.paypal.add.model');
        Route::post('/settings/paypal', [SellerSettingsController::class, 'paypal_add'])->name('seller.paypal.add');
        Route::post('/settings/profile', [SellerSettingsController::class, 'profile_update'])->name('seller.profile.update');
        Route::post('/settings/profile/image', [SellerSettingsController::class, 'profile_image'])->name('seller.profile.image');
        Route::post('/settings/proFile/password', [SellerSettingsController::class, 'profile_password'])->name('seller.profile.password');
        Route::post('/settings/bank/delete', [SellerSettingsController::class, 'bank_delete'])->name('seller.bank.del');
        Route::post('/settings/paypal/delete', [SellerSettingsController::class, 'paypal_delete'])->name('seller.paypal.del');
    });
});

/*Warehouse Admin*/
Route::middleware(['auth', 'w_admin', 'verified'])->prefix('warehouse-admin')->group(function () {
    Route::get('/', [WadminController::class, 'index'])->name('wadmin.dashboard');
    Route::post('/theme', [WadminSettingsController::class, 'index'])->name('wadmin.theme');
    Route::get('/get_noti', [WadminSettingsController::class, 'get_noti'])->name('wadmin.get.noti');
    Route::post('/get_noti/see', [WadminSettingsController::class, 'get_noti_see'])->name('wadmin.get.noti.see');
    Route::get('/notifications', [WadminSettingsController::class, 'notification'])->name('wadmin.notifications');
    Route::get('/notifications/mark-all-as-read', [WadminSettingsController::class, 'notification_read'])->name('wadmin.notifications.read');
    /*Warehouse Management*/
    Route::get('/warehouse-overview', [WadminWarehouseController::class, 'overview'])->name('wadmin.warehouse.overview');
    Route::get('/warehouse-overview-get_block_list/{id}', [WadminWarehouseController::class, 'get_block_list']);
    Route::get('/warehouse-overview-get_rack_list/{id}', [WadminWarehouseController::class, 'get_rack_list']);
    Route::get('/warehouse-overview-get_shelf_list/{id}', [WadminWarehouseController::class, 'get_shelf_list']);
    Route::post('/warehouse-stock-placement/{id}', [WadminWarehouseController::class, 'stock_placement'])->name('wadmin.stockplacement');

    /*Invoice Management*/
    Route::get('/company-invoices', [WadminInvoiceController::class, 'company_invoice'])->name('wadmin.company.invoice');
    Route::get('/company-invoice/view/{id}', [WadminInvoiceController::class, 'invoice_view'])->name('wadmin.company.invoice.view');
    Route::post('/company-invoice/status/{id}', [WadminInvoiceController::class, 'invoice_status'])->name('wadmin.invoice.status');
    Route::post('/return_view/status/{id}', [WadminInvoiceController::class, 'invoice_return_status'])->name('wadmin.invoice.return.status');
    Route::get('/company-invoice/return/view/{id}', [WadminInvoiceController::class, 'return_view'])->name('wadmin.company.return.view');
    Route::get('/company-invoice/return', [WadminInvoiceController::class, 'invoice_return'])->name('wadmin.company.invoice.returns');

    #Orders
    Route::get('/admin/side/new/orders', [WadminOrderManagementController::class, 'woo_new_orders'])->name('wadmin.new.order');
    Route::get('/admin/side/orders/management', [WadminOrderManagementController::class, 'woo_orders_management'])->name('wadmin.order.management');
    Route::get('/admin/order/checkout/view/{id}', [WadminOrderManagementController::class, 'invoice_checkout_view'])->name('wadmin.order.checkout.view');
    Route::post('/admin/order/scanner', [WadminOrderManagementController::class, 'scanner_product'])->name('wadmin.order.scanner');

    //  for order stock
    Route::get('/product_place/tracking/page/stock_out/{id}', [WadminOrderManagementController::class, 'product_place_tracking_page'])->name('wadmin.order.product.place.tracking.page.out.for.stock');
    // Route::get('/product_place/scanner/stock_out/{id}', [WadminOrderManagementController::class , 'scanner_Stock_out'])->name('wadmin.order.product.place.scanner_Stock_out');
    Route::get('/product_place/tracking/stock_out/{id}/{order_id}', [WadminOrderManagementController::class, 'product_place_stock_out'])->name('wadmin.product.place.stock.out');
    Route::post('/stockOut_place/get/stock/out/length/for/order', [WadminOrderManagementController::class, 'get_stock_out_length'])->name('wadmin.stock.out.get.stock.length.for.order');
    Route::post('/stockOut_place/shelf/stock/out/for/order', [WadminOrderManagementController::class, 'shelf_stock_out'])->name('wadmin.stock.place.stock_out_for_order');
    Route::post('/stockOut_place/shelf/stock/del/order', [WadminOrderManagementController::class, 'shelf_stockout_del'])->name('wadmin.stockOut.place.stock.del.for.order');
    Route::post('/stockOut_place/shelf/stock/minus/order', [WadminOrderManagementController::class, 'shelf_stockout_minus'])->name('wadmin.stockOut.place.stock.minus.for.order');
    Route::post('/stockOut_place/shelf/stock/plus/order', [WadminOrderManagementController::class, 'shelf_stockout_plus'])->name('wadmin.stockOut.place.stock.plus.for.order');
    Route::post('/stockOut_place/shelf/stock/update/order/{id}', [WadminOrderManagementController::class, 'shelf_stockOut_update'])->name('wadmin.stockOut.place.stock.update.for.order');
    Route::post('/stockOut_place/shelf/stock/out/order', [WadminOrderManagementController::class, 'shelf_stock_out'])->name('wadmin.stock.place.stock_out.for.order');


    Route::get('/order/tracking/{id}', [WadminOrderManagementController::class, 'order_tracking'])->name('wadmin.order.tracking');
    Route::post('/order/status/set/{id}', [WadminOrderManagementController::class, 'order_status'])->name('wadmin.order.status.set');
    Route::get('Add/shipping/page/{id}', [WadminOrderManagementController::class, 'add_shipping_page'])->name('wadmin.add.shipping.page');
    Route::post('/SMSA/cancel/shipping', [WadminOrderManagementController::class, 'cancel_smsa_shipment'])->name('wadmin.SMSA.cancel.shipping');
    Route::post('/SMSA/add/shipping', [WadminOrderManagementController::class, 'add_smsa_shipment'])->name('wadmin.SMSA.add.shipping');
    Route::post('send/order/to/shipping/company{id}', [WadminOrderManagementController::class, 'send_order'])->name('wadmin.send.order.to.shipping.company');
    Route::get('/SMSA/shipping/get/PDF/{order_id}', [WadminOrderManagementController::class, 'get_PDF'])->name('wadmin.get.pdf');
    Route::get('/system/shipping/get/PDF/{id}', [WadminOrderManagementController::class, 'system_waybill'])->name('wadmin.get.system.waybill');
    Route::get('/refunded/orders/index', [WadminOrderManagementController::class, 'woo_orders_refunded_index'])->name('wadmin.woo.refunded.order.list');
    Route::get('/refunded/order/view/{id}', [WadminOrderManagementController::class, 'refunded_order_view'])->name('wadmin.refunded.order.view');
    Route::post('/refunded/order/received/{id}', [WadminOrderManagementController::class, 'refunded_order_received'])->name('wadmin.refunded.order.received');

    /*Stock IN in order return*/
    Route::get('/stockIn_place/view/order/return/{id}', [WadminOrderManagementController::class, 'stock_in_view'])->name('wadmin.stockIn.place.view.order.return');
    Route::get('/stockIn_place/shelf/stock/order/return', [WadminOrderManagementController::class, 'stock_in_add'])->name('wadmin.stockIn.place.add.order.return');
    Route::post('/stockIn_place/shelf/stock/in/order/return', [WadminOrderManagementController::class, 'shelf_stock_in'])->name('wadmin.stockIn.place.update.order.return');
    Route::post('/stockIn_place/get/stock/length/order/return', [WadminOrderManagementController::class, 'get_stock_length'])->name('wadmin.stockIn.get.stock.length.order.return');
    Route::get('/stockIn_place/shelf/stock/data/order/return', [WadminOrderManagementController::class, 'shelf_stockIn_data'])->name('wadmin.stockIn.place.data.order.return');
    Route::post('/stockIn_place/shelf/stock/update/order/return/{id}', [WadminOrderManagementController::class, 'shelf_stockIn_update'])->name('wadmin.stockIn.place.stock.update.order.return');
    Route::post('/stockIn_place/shelf/stock/del/order/return', [WadminOrderManagementController::class, 'shelf_stockIn_del'])->name('wadmin.stockIn.place.stock.del.order.return');
    Route::post('/stockIn_place/shelf/stock/minus/order/return', [WadminOrderManagementController::class, 'shelf_stockIn_minus'])->name('wadmin.stockIn.place.stock.minus.order.return');
    Route::post('/stockIn_place/shelf/stock/plus/order/return', [WadminOrderManagementController::class, 'shelf_stockIn_plus'])->name('wadmin.stockIn.place.stock.plus.order.return');


    /*Stock Management*/
    Route::get('/stock_by_warehouse', [WadminStockController::class, 'stock_by_warehouse'])->name('wadmin.stock.warehouse');
    Route::post('/stock_by_warehouse/filter', [WadminStockController::class, 'stock_by_warehouse_filter'])->name('wadmin.stock.warehouse.filter');

    /*Placement Management*/

    /*Stock IN*/
    Route::get('/stockIn_place/view', [PlacementController::class, 'stock_in_view'])->name('wadmin.stockIn.place.view');
    Route::get('/stockIn_place/shelf/stock', [PlacementController::class, 'stock_in_add'])->name('wadmin.stockIn.place.add');
    Route::post('/stockIn_place/shelf/stock/in', [PlacementController::class, 'shelf_stock_in'])->name('wadmin.stockIn.place.update');
    Route::post('/stockIn_place/get/stock/length', [PlacementController::class, 'get_stock_length'])->name('wadmin.stockIn.get.stock.length');
    Route::get('/stockIn_place/shelf/stock/data', [PlacementController::class, 'shelf_stockIn_data'])->name('wadmin.stockIn.place.data');
    Route::post('/stockIn_place/shelf/stock/update/{id}', [PlacementController::class, 'shelf_stockIn_update'])->name('wadmin.stockIn.place.stock.update');
    Route::post('/stockIn_place/shelf/stock/del', [PlacementController::class, 'shelf_stockIn_del'])->name('wadmin.stockIn.place.stock.del');
    Route::post('/stockIn_place/shelf/stock/minus', [PlacementController::class, 'shelf_stockIn_minus'])->name('wadmin.stockIn.place.stock.minus');
    Route::post('/stockIn_place/shelf/stock/plus', [PlacementController::class, 'shelf_stockIn_plus'])->name('wadmin.stockIn.place.stock.plus');
    Route::post('/stock/placement/on-off', [PlacementController::class, 'on_off'])->name('wadmin.stock.placement.on_off');

    /*Stock Out*/
    Route::get('/stockOut_place/view', [PlacementController::class, 'stock_out_view'])->name('wadmin.stockOut.place.view');
    Route::get('/stockOut_place/shelf/stock', [PlacementController::class, 'stock_out_add'])->name('wadmin.stockOut.place.add');
    Route::post('/stockIn_place/get/stock/out/length', [PlacementController::class, 'get_stock_out_length'])->name('wadmin.stock.out.get.stock.length');
    Route::post('/stockOut_place/shelf/stock/out', [PlacementController::class, 'shelf_stock_out'])->name('wadmin.stock.place.stock_out');
    Route::get('/stockOut_place/shelf/stock/data', [PlacementController::class, 'shelf_stockOut_data'])->name('wadmin.stockOut.place.data');
    Route::post('/stockOut_place/shelf/stock/update/{id}', [PlacementController::class, 'shelf_stockOut_update'])->name('wadmin.stockOut.place.stock.update');
    Route::get('/stockOut_place/shelf/stock/history', [PlacementController::class, 'shelf_stock_history'])->name('wadmin.stock.place.history');
    Route::post('/stockOut_place/shelf/stock/del', [PlacementController::class, 'shelf_stockout_del'])->name('wadmin.stockOut.place.stock.del');
    Route::post('/stockOut_place/shelf/stock/minus', [PlacementController::class, 'shelf_stockout_minus'])->name('wadmin.stockOut.place.stock.minus');
    Route::post('/stockOut_place/shelf/stock/plus', [PlacementController::class, 'shelf_stockout_plus'])->name('wadmin.stockOut.place.stock.plus');

    /*Stock Move*/
    Route::get('/stockMove_place/view', [PlacementController::class, 'stock_move_view'])->name('wadmin.stockMove.place.view');
    Route::get('/stockMove_place/shelf/stock', [PlacementController::class, 'stock_move_add'])->name('wadmin.stockMove.place.add');
    Route::post('/stockMove_place/get/stock/out/length', [PlacementController::class, 'get_stock_move_length'])->name('wadmin.stock.move.get.stock.length');
    Route::post('/stockMove_place/shelf/stock/out', [PlacementController::class, 'shelf_stock_move'])->name('wadmin.stock.place.stock_move');
    Route::get('/stockMove_place/shelf/stock/data', [PlacementController::class, 'shelf_stockmove_data'])->name('wadmin.stockMove.place.data');
    Route::get('/stockMove_place/shelf/stock/move/{id}', [PlacementController::class, 'shelf_stockmove_move'])->name('wadmin.stockMove.place.stock.move');
    Route::post('/stockMove_place/shelf/stock/update', [PlacementController::class, 'shelf_stockmove_update'])->name('wadmin.stockMove.place.update');
    Route::post('/stockMove_place/shelf/stock/del', [PlacementController::class, 'shelf_stockmove_del'])->name('wadmin.stockMove.place.stock.del');
    Route::post('/stockMove_place/shelf/stock/minus', [PlacementController::class, 'shelf_stockmove_minus'])->name('wadmin.stockMove.place.stock.minus');
    Route::post('/stockMove_place/shelf/stock/plus', [PlacementController::class, 'shelf_stockmove_plus'])->name('wadmin.stockMove.place.stock.plus');

    //    Route::get('/stock_place/shelf/stock/history', [PlacementController::class , 'shelf_stock_history'])->name('wadmin.stock.place.history');

    /*Stock tracking*/
    Route::get('/product_place/tracking/page', [PlacementController::class, 'product_place_tracking_page'])->name('wadmin.product.place.tracking.page');
    Route::post('/product_place/tracking', [PlacementController::class, 'product_place_tracking'])->name('wadmin.product.place.tracking');

    #settings
    Route::get('/barcode/pdf/download/{type}/{id}/{qty}', [WadminSettingsController::class, 'barcode_download'])->name('wadmin.barcode.download');
    Route::get('/barcode/pdf/download/allproducts/{id}', [WadminSettingsController::class, 'all_barcode_download'])->name('wadmin.all.barcode.download');
    Route::get('/settings/profile', [WadminSettingsController::class, 'profile'])->name('wadmin.profile');
    Route::post('/settings/profile', [WadminSettingsController::class, 'profile_update'])->name('wadmin.profile.update');
    Route::post('/settings/profile/image', [WadminSettingsController::class, 'profile_image'])->name('wadmin.profile.image');
    Route::post('/settings/profile/Password', [WadminSettingsController::class, 'profile_password'])->name('wadmin.profile.password');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
require __DIR__ . '/auth.php';

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/clear-cache', function() { Cache::flush(); return 'Cache cleared'; });
