<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HubspotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CommissionController;

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

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::group(["middleware" => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', [UserController::class, "dashboard_view"])->name('dashboard');


    /*** Users Routes ***/

    Route::get('/user', [UserController::class, "index_view"])->name('user');
    Route::view('/user/new', "pages.user.user-new")->name('user.new');
    Route::view('/user/edit/{userId}', "pages.user.user-edit")->name('user.edit');

    /*** Product Amount Routes ***/

    Route::get('/product-amount', [OrderController::class, "product_amount_view"])->name('product-amount');
    Route::view('/product-amount/new', "pages.product-amount.product-amount-new")->name('product-amount.new');
    Route::view('/product-amount/edit/{productAmountId}', "pages.product-amount.product-amount-edit")->name('product-amount.edit');

    /*** Payment Routes ***/

    Route::get('/payment-method', [PaymentController::class, "method_index_view"])->name('payment.method');
    Route::view('/payment-method/new', "pages.payment-method.payment-method-new")->name('payment.method.new');
    Route::view('/payment-method/edit/{paymentMethodId}', "pages.payment-method.payment-method-edit")->name('payment.method.edit');

    /*** Order Routes ***/

    Route::get('/order', [OrderController::class, 'index_view'])->name('order');
    Route::view('/order/contact/{contactId}', "pages.order.order-new")->name('contact.order');
    Route::view('/order/new/', "pages.order.order-new")->name('order.new');
    Route::view('/order/edit/{orderId}', "pages.order.order-edit")->name('order.edit');
    Route::get('/order/view/{orderId}', [OrderController::class, 'view'])->name('order.view');
    Route::get('/contact', [HubspotController::class, "contact_view"])->name('contact');



    /*** Agent Routes ***/

    Route::get('/agent/list', [HubspotController::class, "list_agent_view"])->name('agent.list');
    Route::get('/agent', [CommissionController::class, "agent_view"])->name('agent');
    Route::view('/agent/new', "pages.agent.agent-new")->name('agent.new');
    Route::view('/agent/new/{hubspotId}', "pages.agent.agent-new")->name('agent.generate');
    Route::view('/agent/edit/{agentId}', "pages.agent.agent-edit")->name('agent.edit');


    /*** Product Routes ***/

    Route::get('/product/list', [OrderController::class, "list_product_view"])->name('product.list');
    Route::get('/product', [OrderController::class, "product_view"])->name('product');
    Route::view('/product/new/{elopageProductId}', "pages.product.product-new")->name('product.generate');
    Route::view('/product/edit/{productId}', "pages.product.product-edit")->name('product.edit');

    /*** Commission Percentage Routes ***/

    Route::get('/commission-percentage', [CommissionController::class, "percentage_view"])->name('commission-percentage');
    Route::view('/commission-percentage/new', "pages.commission-percentage.commission-percentage-new")->name('commission-percentage.new');
    Route::view('/commission-percentage/edit/{commissionPercentageId}', "pages.commission-percentage.commission-percentage-edit")->name('commission-percentage.edit');

  /*** Set Language for the app ***/

    Route::get('/language/{code}', [UserController::class, "set_language"])->name('language.set');

    Route::get('/commission/overview', [HubspotController::class, "commission_overview"])->name('commission');

    Route::post('/order/save', [OrderController::class, 'save'])->name('order.save');

    Route::get('/elopage/products', [HubspotController::class, 'elopage_product'])->name('test.elopage');

    /***
    Route::get('/order', [OrderController::class, 'index_view'])->name('order');
    Route::get('/order/new/{contactId}', [OrderController::class, 'order_view'])->name('order.new');
    Route::get('/order/edit/{id}', [OrderController::class, 'edit_view'])->name('order.edit');
    Route::put('/order/update', [OrderController::class, 'update'])->name('order.update');
     ***/

    /*** Payment Routes ***/
    Route::get('/payment', [ PaymentController::class, "index_view" ])->name('payment');
    Route::get('/payment/{orderId}', [ PaymentController::class, "single_view" ])->name('payment.view');

    //"pages.payment.payment-view"

    Route::get('stripe/{orderId}', [PaymentController::class, 'stripe']);
    Route::post('stripe', [PaymentController::class, 'stripePost'])->name('stripe.post');


    /*** Test Routes ***/
    Route::get('/test/order/{orderId}', [OrderController::class, "create_elopage_order"])->name('test.order');
    Route::get('/test/invoice/{orderId}', [OrderController::class, "create_sevdesk_invoice"])->name('test.invoice');
    Route::get('/test/sendinvoice/{orderId}', [OrderController::class, "send_sevdesk_invoice"])->name('test.sendinvoice');
    Route::get('/test/deal/{orderId}', [HubspotController::class, "create_hubspot_deal"])->name('test.deals');
    Route::get('/test/transaction/', [PaymentController::class, "create_transaction"])->name('test.transaction');
    Route::get('/test/bookTransaction/{transactionId}', [OrderController::class, "book_sevdesk_invoice"])->name("test.bookTransaction");
    Route::get('/test/dealIncrement', [PaymentController::class, "deal_increment"])->name("deal.increment");
    Route::get('/test/confirmation/{orderId}', function ($orderId) {
        return new App\Mail\OrderCheckout($orderId);
    });

    Route::get('/do/base64', function () {
        $val = "9ac4b2ba12879d233335def97bb07c88";
        return base64_encode($val);
    })->name('do.base64');

    Route::get('/do/clear-cache', function () {
        $exitCode = Artisan::call('cache:clear');
        return "success";
    })->name('do.clear-cache');

    Route::get('/do/feed_vat', function () {
        $exitCode = Artisan::call('feed:vat_percentage');
        return "success";
    })->name('do.feed_vat');

    /*** Generate Routes ***/
    Route::get('generate/invoice/{orderId}', [OrderController::class, "create_sevdesk_invoice"])->name('generate.invoice');
    Route::get('send/invoice/{orderId}', [OrderController::class, "send_sevdesk_invoice"])->name('send.invoice');
    Route::get('generate/elopage/{orderId}', [OrderController::class, "create_elopage_order"])->name('generate.elopage.order');
    Route::get('generate/contact/{orderId}', [OrderController::class, "create_sevdesk_customer"])->name('generate.contact');
    Route::get('generate/installment/schedule/{orderId}', [OrderController::class, "test_installment_schedule"])->name('generate.installment_schedule');
    Route::get('generate/deal/{orderId}', [HubspotController::class, "create_hubspot_deal"])->name('generate.deal');
    Route::get('fetch/hs-deals', [HubspotController::class, "fetch_hs_deals"])->name('fetch.deals');
    Route::get('order/status/{orderId}/{statusId}', [OrderController::class, "set_order_status"])->name('order.status.set');

});

/*** Checkout process for the User ***/

Route::get('/order/checkout/{orderId}', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/order/checkout/', [OrderController::class, 'process_checkout'])->name('order.checkout.process');


///new
Route::get('/order/email/{orderId}/{id}', [OrderController::class, 'process_email'])->name('order.checkout.email');

Route::post('/order/payment/resume', [OrderController::class, 'process_payment_resume'])->name('order.payment.resume');
Route::get('/order/preoptin/{orderId}', [OrderController::class, 'preoptin'])->name('order.checkout.preoptin');
Route::get('/order/status/{orderId}/{transactionId}/{status}', [OrderController::class, 'order_status'])->name('order.status');
///new
Route::get('/order/status/{orderId}/{transactionId}/{status}/{installmentId}', [OrderController::class, 'order_status_installment'])->name('order.status.installment');

Route::get('/order/optin/{orderId}', [OrderController::class, 'optin'])->name('order.optin');
Route::post('/order/optin/', [OrderController::class, 'process_optin'])->name('order.optin.process');

Route::get('/order/payment/{orderId}/{installmentId?}', [PaymentController::class, 'init'])->name('order.payment');


//new
Route::get('/process/payment/abilita/installment/{orderId}/{installmentId}', [ PaymentController::class, "abilita_init_install" ])->name('payment.abilita.init.install');


/*** Abilita Pay ***/

Route::get('/process/payment/kredit/{orderId}', [ PaymentController::class, "kredit_close_init" ])->name('payment.kredit.init');
Route::get('/process/payment/abilita/{orderId}', [ PaymentController::class, "abilita_init" ])->name('payment.abilita.init');
Route::get('/tp/payment/abilita/{orderId}/transaction/{transactionId}', [ PaymentController::class, "abilita_init" ])->name('payment.abilita.init.transaction');
Route::post('/process/payment/abilita/postback', [ PaymentController::class, "abilita_postback" ])->name('payment.abilita.postback');
Route::get('/process/payment/success/abilita', [ PaymentController::class, "abilita_success" ])->name('payment.abilita.success');
Route::get('/process/payment/error/abilita', [ PaymentController::class, "abilita_error" ])->name('payment.abilita.error');
