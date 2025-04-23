<?php

use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InStockController;
use App\Http\Controllers\DefectiveController;
use App\Http\Controllers\CoreDataController;
use App\Http\Controllers\DeliveryChallanController;
use App\Http\Controllers\GatePassController;
use App\Http\Controllers\WarrantyLogController;
use App\Http\Controllers\ReturnableController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserCreationController;
use App\Http\Controllers\RoleAssignmentController;
use App\Http\Controllers\ViewProfileController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CoreInventoryPrimaryRecordsController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\InvoiceListController;
use App\Http\Controllers\ReturnStatusLogController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\DefectiveItemsReportController;
use App\Http\Controllers\AllDocumentsController;
use App\Http\Controllers\AccessController;




// Show the sign-in page at the root URL
Route::get('/', [AuthController::class, 'showSignInPage'])->name('auth.signin.page');

// Route to handle the sign-in form submission
Route::post('/sign-in', [AuthController::class, 'signIn'])->name('auth.signin');
// Route to handle the logout action
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Forget pass
// Route::get('password/request', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');


// Protected use App\Http\Controllers\UploadController;Routes - Requires Authentication
Route::middleware('auth')->group(function () {

        // Logistics Management  start
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
       

        Route::get('/logistics/in-stock', [InStockController::class, 'index'])->name('logistics.instock');

        Route::get('/logistics/delivery-challan', [App\Http\Controllers\DeliveryChallanController::class, 'index'])->name('logistics.deliverychallan');


        // undone
        Route::get('/logistics/gate-pass', [App\Http\Controllers\GatePassController::class, 'index'])->name('logistics.gatepass');
        // undone


        Route::get('/logistics/returnable', [App\Http\Controllers\ReturnableController::class, 'index'])->name('logistics.returnable');



        Route::get('/logistics/defective', [App\Http\Controllers\DefectiveController::class, 'index'])->name('logistics.defective');


        // undone
        Route::get('/logistics/warranty-log', [App\Http\Controllers\WarrantyLogController::class, 'index'])->name('logistics.warrantylog');
        // undone

        Route::get('/logistics/core-data', [App\Http\Controllers\CoreDataController::class, 'index'])->name('logistics.coredata');

        Route::get('/logistics/alldocuments', [AllDocumentsController::class, 'index'])->name('logistics.alldocuments');
        // Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');

        Route::get('/documents/{lcpoNo}/download', [AccessController::class, 'download'])->name('documents.download');

        // Logistics Management end

        // data entry start
        

        Route::get('/coreinventory/primary-records', [App\Http\Controllers\CoreInventoryPrimaryRecordsController::class, 'primaryRecords'])
        ->name('coreinventory.primaryrecords');

        // Route to handle form submission and store the data
        Route::post('/coreinventory/primary-records', [App\Http\Controllers\CoreInventoryPrimaryRecordsController::class, 'store'])
        ->name('coreinventory.store');
       

        // Route to load the data entry form page
        Route::get('/data-entry', [DataEntryController::class, 'index'])
            ->name('dataentry.index');

        Route::post('/get-brands', [DataEntryController::class, 'getBrands'])
            ->name('get.brands');
        
        // Route to handle form submission and store the data
        Route::post('/data-entry', [DataEntryController::class, 'store'])
            ->name('dataentry.store');

        // data entry end

        // Delivery challan start



        
        // Route for Invoice List
        Route::get('/logistics/invoice-list', [App\Http\Controllers\InvoiceListController::class, 'index'])
        ->name('logistics.invoicelist');

        // Route for Confirm Invoice (GET - Display Confirmation Page)
        Route::get('/logistics/invoice-confirm', [App\Http\Controllers\ConfirmInvoiceController::class, 'index'])
            ->name('logistics.invoiceconfirm');

        // Route for Confirm Invoice (POST - Process Selected Items)
        Route::post('/logistics/invoice-confirm', [App\Http\Controllers\ConfirmInvoiceController::class, 'store'])
            ->name('logistics.invoiceconfirm.store');


        Route::get('/logistics/invoice-list', [App\Http\Controllers\InvoiceListController::class, 'index'])->name('logistics.invoicelist');

        // Route for Confirm Invoice
        Route::post('/logistics/invoice-confirm', [App\Http\Controllers\ConfirmInvoiceController::class, 'store'])->name('logistics.invoiceconfirm');

       // Route for displaying the invoice confirmation form
        Route::get('/logistics/invoice-confirm', [App\Http\Controllers\ConfirmInvoiceController::class, 'index'])->name('logistics.invoiceconfirm');

        // Route for handling the POST request from the invoice confirmation form
        Route::post('/logistics/invoice-confirm', [App\Http\Controllers\ConfirmInvoiceController::class, 'store'])->name('logistics.invoiceconfirm.store');
 
         
        // Route for displaying the latest invoice number
        Route::get('/logistics/show-invoice-number', [App\Http\Controllers\ConfirmInvoiceController::class, 'showInvoiceNumber'])
        ->name('logistics.showInvoiceNumber');


        // Route for showing the next invoice number
        Route::get('/invoice/show-number', [ConfirmInvoiceController::class, 'showInvoiceNumber'])->name('invoice.showNumber');
       
        // Route for Invoice Download
        Route::get('/logistics/invoice-download', [App\Http\Controllers\InvoiceDownloadController::class, 'index'])->name('logistics.invoicedownload');

        // Route for handling form submission
        Route::post('/logistics/invoice-download', [App\Http\Controllers\InvoiceDownloadController::class, 'store'])->name('logistics.invoicedownload');

        // Route for Edit Invoice
        Route::get('/logistics/invoice-edit', [App\Http\Controllers\EditInvoiceController::class, 'index'])->name('logistics.invoiceedit');
        // Delivery challan end



        // This route renders the form
        Route::get('/upload/local-file-upload', [UploadController::class, 'localFileUpload'])->name('upload.localFileUpload');

        //Route::post('/upload-file', [UploadController::class, 'storeLocalFileUpload'])->name('upload.storeLocalFileUpload');
        Route::post('/upload/store', [UploadController::class, 'storeLocalFileUpload'])->name('upload.storeLocalFileUpload');


       // This route renders the form
        Route::get('/upload/import-file-upload', [UploadController::class, 'importFileUpload'])->name('upload.importFileUpload');

        // This route handles the form submission
        Route::post('/upload/import-store', [UploadController::class, 'storeImportFileUpload'])->name('upload.storeImportFileUpload');

        // upload File end

       
        // Challan Management Return Start
        Route::get('/logistics/return-status-log', [ReturnStatusLogController::class, 'index'])->name('logistics.returnstatuslog');
        Route::prefix('logistics')->name('logistics.')->group(function () {
            Route::get('/return-status-log', [App\Http\Controllers\ReturnStatusLogController::class, 'index'])->name('returnstatuslog');
            Route::post('/return-status-log/update/{id}', [App\Http\Controllers\ReturnStatusLogController::class, 'updateStatus'])->name('returnstatuslog.update');
            Route::delete('/return-status-log/destroy/{id}', [App\Http\Controllers\ReturnStatusLogController::class, 'destroy'])->name('returnstatuslog.destroy');

        });

                // Laravel Route Definition
        Route::post('/logistics/return-status-log/update-item-status', [App\Http\Controllers\ReturnStatusLogController::class, 'updateItemStatus'])->name('logistics.returnstatuslog.updateItemStatus');

        // Challan Management Return End



        // Report Section Start
        Route::get('/current-stock-levels', [StockReportController::class, 'showStockReport'])->name('currentstocklevels');

        Route::get('/po-stock-report', [StockReportController::class, 'poStockReport'])->name('postockreport');
        
        Route::get('/lc-wise-stock', [StockReportController::class, 'lcWiseStock'])->name('lcwisestock');


        Route::get('/defective-items-report', [DefectiveItemsReportController::class, 'defectiveProductsReport'])->name('defectiveitems');
 

      


        
        // Route::get('/product-warranty-overview', [WarrantyReportController::class, 'productWarrantyOverview'])->name('productwarranty');
        // Route::get('/revenue-summary', [RevenueReportController::class, 'revenueSummary'])->name('revenuesummary');


        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/current-stock-levels', [StockReportController::class, 'showStockReport'])->name('currentstocklevels');
            Route::get('/stock-report', [StockReportController::class, 'showStockReport'])->name('stockreport');

            Route::get('/po-stock-report', [StockReportController::class, 'poStockReport'])->name('postockreport');
            Route::get('/lc-wise-stock', [StockReportController::class, 'lcWiseStock'])->name('lcwisestock');
            Route::get('/defective-items-report', [DefectiveItemsReportController::class, 'defectiveProductsReport'])->name('defectiveitems');
            // Route::get('/product-warranty-overview', [WarrantyReportController::class, 'productWarrantyOverview'])->name('productwarranty');
            // Route::get('/revenue-summary', [RevenueReportController::class, 'revenueSummary'])->name('revenuesummary');
        });

        // Report Section End



        // Security start
        // Route for User List Page
        Route::get('/user-list', [App\Http\Controllers\UserListController::class, 'index'])->name('user.list');


        // Route for User Creation
        // Route for User Creation Page
        Route::get('/user-create', [App\Http\Controllers\UserCreationController::class, 'index'])->name('user.create');
        Route::post('/user-store', [UserCreationController::class, 'store'])->name('user.store');

        // Route for Role Assignment Page
        Route::get('/role-assign/{user}', [App\Http\Controllers\RoleAssignmentController::class, 'index'])->name('role.assign');

        
        // Route for View Profile Page
        Route::get('/user-profile/{user}', [App\Http\Controllers\ViewProfileController::class, 'index'])->name('user.profile');
        
        // Route for Role Assignment page (users-grid.php)
        Route::get('/role-assignment/{user}', [RoleAssignmentController::class, 'index'])->name('role.assignment');
        
        // View profile
        Route::get('/view-profile/{user}', [ViewProfileController::class, 'index'])->name('view.profile');

        // Security end


        // Access point start

          Route::get('/accessing', [App\Http\Controllers\AccessController::class, 'show'])->name('accessing');
        // Access point end
        


});