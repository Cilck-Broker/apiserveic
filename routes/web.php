<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\FollowupController;
use App\Http\Controllers\SendingEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\scheduleController;
use App\Http\Controllers\InvoiceController;

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

    Auth::routes();
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('/schSendEmailRenew',    [scheduleController::class, 'sendEmailRenew']);

    Route::post('/getCusDataList',      [HomeController::class, 'getCusDataList']);
    Route::post('/getStatusOrders',     [HomeController::class, 'getStatusOrders']);
    Route::post('/getAgentCode',        [HomeController::class, 'getAgentCode']);
    
    Route::get('/editOrder/{id}',       [HomeController::class, 'editOrderPage']);
    
    Route::post('/getOrderDetailById', [OrdersController::class, 'getOrderDetailById']);
    Route::post('/followupType',    [OrdersController::class, 'followupType']);
    Route::post('/insertFollowup',  [FollowupController::class, 'create']);
    Route::post('/getFollowupByOrderID', [FollowupController::class, 'getFollowupByOrderID']);
    Route::post('/updateCustomerData',  [OrdersController::class, 'updateCustomerData']);
    Route::post('/sendEmailComfirm',    [OrdersController::class, 'sendEmailComfirm']);
    Route::post('/sendEmailRenew',      [OrdersController::class, 'sendEmailRenew']);
    Route::post('/btnCreateInvoice',    [OrdersController::class, 'btnCreateInvoice']);
    Route::post('/CancelInvoice',       [OrdersController::class, 'CancelInvoice']);
    Route::post('/uploadFileOrder',     [OrdersController::class, 'uploadFileOrder']);
    Route::post('/DelOrdersFile',       [OrdersController::class, 'DelOrdersFile']);

    Route::get('/invoice',              [InvoiceController::class, 'getDataInvoicePaid']);  
    Route::post('/exportExcelInvoice',  [InvoiceController::class, 'exportExcelInvoice']);
    Route::post('/getInvoiceDataList',  [InvoiceController::class, 'getInvoiceDataList']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/login', function () {
        return view('login'); // แสดงหน้า login สำหรับการทดสอบเมธอด GET
    });
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/renewcustomer/{id}', [PdfController::class, 'viewpdfrenew']); 
    Route::get('/comfirmcustomer/{id}', [PdfController::class, 'viewpdf']);

    
    



