<?php

use Illuminate\Support\Facades\Route;

//FRONT ============================================================================
Route::get('/home', [
    'uses' => 'FrontController@showHome',
    'as' => 'home'
]);

Route::get('/forgot-password', [
    'uses' => 'FrontController@showForgotPassword',
    'as' => 'forgot-password'
]);

Route::get('/products', [
    'uses' => 'FrontController@showProducts',
    'as' => 'products'
]);

Route::get('/product-detail', [
    'uses' => 'FrontController@showProductDetail',
    'as' => 'product-detail'
]);

Route::get('/centers', [
    'uses' => 'FrontController@showCenters',
    'as' => 'centers'
]);

Route::get('/news-events', [
    'uses' => 'FrontController@showNewsEvents',
    'as' => 'news-events'
]);

Route::get('/news-event-detail', [
    'uses' => 'FrontController@showNewsEventDetail',
    'as' => 'news-event-detail'
]);

Route::get('/faq', [
    'uses' => 'FrontController@showFAQ',
    'as' => 'faq'
]);

Route::get('/about-us', [
    'uses' => 'FrontController@showAboutUs',
    'as' => 'about-us'
]);

Route::get('/contact-us', [
    'uses' => 'FrontController@showContactUs',
    'as' => 'contact-us'
]);

Route::get('/privacy-policy', [
    'uses' => 'FrontController@showPrivacyPolicy',
    'as' => 'privacy-policy'
]);

Route::get('/terms-and-conditions', [
    'uses' => 'FrontController@showTermsAndConditions',
    'as' => 'terms-and-conditions'
]);

Route::get('/shipping-cancellation-return-policy', [
    'uses' => 'FrontController@showShippingCancellationReturnPolicy',
    'as' => 'shipping-cancellation-return-policy'
]);

Route::post('/send-message', [
    'uses' => 'FrontController@doSendMessage',
    'as' => 'send-message'
]);

Route::get('/cart', [
    'uses' => 'FrontController@showCart',
    'as' => 'cart'
]);

Route::post('/add-to-cart-with-qty-background', [
    'uses' => 'FrontController@AddToCartWithQtyBackground',
    'as' => 'add-to-cart-with-qty-background'
]);

Route::post('/add-items-to-cart', [
    'uses' => 'FrontController@AddToCartMoreQty',
    'as' => 'add-items-to-cart'
]);

Route::post('/update-item-cart-single', [
    'uses' => 'FrontController@UpdateItemCartSingle',
    'as' => 'update-item-cart-single'
]);

Route::post('/update-item-cart', [
    'uses' => 'FrontController@UpdateItemCart',
    'as' => 'update-item-cart'
]);

Route::post('/remove-item-cart', [
    'uses' => 'FrontController@RemoveItemCart',
    'as' => 'remove-item-cart'
]);

Route::get('/checkout', [
    'uses' => 'FrontController@showCheckout',
    'as' => 'checkout'
]);

Route::post('/do-check-shipping-charges', [
    'uses' => 'FrontController@doCheckShippingCharges',
    'as' => 'do-check-shipping-charges'
]);

Route::post('/do-checkout-order', [
    'uses' => 'FrontController@doCheckoutOrder',
    'as' => 'do-checkout-order'
]);

Route::get('/checkout-response', [
    'uses' => 'FrontController@showCheckoutResponse',
    'as' => 'checkout-response'
]);

//ADMIN ============================================================================

Route::get('/admin-login', [
    'uses' => 'AdminController@showAdminLogin',
    'as' => 'admin-login'
]);

Route::post('/do-admin-login', [
    'uses' => 'AdminController@doAdminLogin',
    'as' => 'do-admin-login'
]);

Route::post('/do-admin-change-password', [
    'uses' => 'AdminController@doChangePassword',
    'as' => 'do-admin-change-password'
]);

Route::get('/admin-logout', [
    'uses' => 'AdminController@doAdminLogout',
    'as' => 'admin-logout'
]);

Route::get('/admin-dashboard', [
    'uses' => 'AdminController@showDashboard',
    'as' => 'admin-dashboard'
]);

Route::get('/admin-member-tree', [
    'uses' => 'AdminController@showMemberTree',
    'as' => 'admin-member-tree'
]);

//CENTER =======================================================================================================================================
Route::get('/admin-center-management', [
    'uses' => 'AdminController@showCenterManagement',
    'as' => 'admin-center-management'
]);

Route::post('/get-center-list', [
    'uses' => 'AdminController@getCenterList',
    'as' => 'get-center-list'
]);

Route::post('/get-center-info', [
    'uses' => 'AdminController@getCenterInfo',
    'as' => 'get-center-info'
]);

Route::post('/do-save-center', [
    'uses' => 'AdminController@doSaveCenter',
    'as' => 'do-save-center'
]);
//WIRES =======================================================================================================================================
Route::get('/admin-wirecode', [
    'uses' => 'AdminController@showWireCode',
    'as' => 'admin-wirecode'
]);
Route::get('/admin-wire-history', [
    'uses' => 'AdminController@showWireHistory',
    'as' => 'admin-wire-history'
]);
Route::get('/admin-member-active-wire', [
    'uses' => 'AdminController@showMemberActiveWire',
    'as' => 'admin-member-active-wire'
]);

Route::post('/get-wirecode', [
    'uses' => 'WirecodeController@index',
    'as' => 'get-wirecode'
]);
Route::post('/do-save-wire', [
    'uses' => 'WirecodeController@store',
    'as' => 'do-save-wire'
]);
Route::post('/do-update-wire', [
    'uses' => 'WirecodeController@update',
    'as' => 'do-update-wire'
]);
Route::post('/do-search-wire', [
    'uses' => 'WirecodeController@search_data',
    'as' => 'do-search-wire'
]);
Route::post('/do-search-active-wire', [
    'uses' => 'WirecodeController@search_active_wire',
    'as' => 'do-search-active-wire'
]);
Route::post('/get-wirecode-history', [
    'uses' => 'WirecodeController@fetch_wirecode_active',
    'as' => 'get-wirecode-history'
]);

Route::post('/do-search-member-active-wire', [
    'uses' => 'WirecodeController@search_member_active_wire',
    'as' => 'do-search-member-active-wire'
]);
Route::post('/get-member-active-wire', [
    'uses' => 'WirecodeController@fetch_member_active_wire',
    'as' => 'get-member-active-wire'
]);

//CODES =======================================================================================================================================
Route::get('/admin-code-generation', [
    'uses' => 'AdminController@showCodeGeneration',
    'as' => 'admin-code-generation'
]);

Route::post('/get-code-generation-batch-list', [
    'uses' => 'AdminController@getCodeGenerationBatchList',
    'as' => 'get-code-generation-batch-list'
]);

Route::post('/get-code-generation-batch-info', [
    'uses' => 'AdminController@getCodeGenerationBatchInfo',
    'as' => 'get-code-generation-batch-info'
]);

Route::post('/do-code-generation-batch', [
    'uses' => 'AdminController@doSaveCodeGenerationBatch',
    'as' => 'do-code-generation-batch'
]);

Route::post('/do-approve-code-generation-batch', [
    'uses' => 'AdminController@doApproveCodeGenerationBatch',
    'as' => 'do-approve-code-generation-batch'
]);

Route::post('/get-code-generation-by-batch', [
    'uses' => 'AdminController@getCodeGenerationByBatch',
    'as' => 'get-code-generation-by-batch'
]);

Route::post('/do-cancel-code-generation-batch', [
    'uses' => 'AdminController@doCancelCodeGenerationBatch',
    'as' => 'do-cancel-code-generation-batch'
]);

Route::post('/do-cancel-code', [
    'uses' => 'AdminController@doCancelCode',
    'as' => 'do-cancel-code'
]);

Route::post('/get-code-generation-list', [
    'uses' => 'AdminController@getCodeGenerationList',
    'as' => 'get-code-generation-list'
]);

Route::get('/get-code-generation-search-list', [
    'uses' => 'AdminController@getCodeGenerationSearchList',
    'as' => 'get-code-generation-search-list'
]);

Route::post('/get-code-generation-info', [
    'uses' => 'AdminController@getCodeGenerationInfo',
    'as' => 'get-code-generation-info'
]);

Route::post('/do-issue-code-generation', [
    'uses' => 'AdminController@doIssueCodeGeneration',
    'as' => 'do-issue-code-generation'
]);

Route::get('/admin-print-codes', [
    'uses' => 'AdminController@PrintCodes',
    'as' => 'admin-print-codes'
]);

Route::get('/admin-code-distribution', [
    'uses' => 'AdminController@showCodeDistribution',
    'as' => 'admin-code-distribution'
]);


//MEMBERS =======================================================================================================================================
Route::get('/admin-member-management', [
    'uses' => 'AdminController@showMemberManagement',
    'as' => 'admin-member-management'
]);

Route::get('/admin-member-genealogy', [
    'uses' => 'AdminController@showMemberGenealogy',
    'as' => 'admin-member-genealogy'
]);

Route::post('/get-member-list', [
    'uses' => 'AdminController@getMemberList',
    'as' => 'get-member-list'
]);

Route::get('/get-member-search-list', [
    'uses' => 'AdminController@getMemberSearchList',
    'as' => 'get-member-search-list'
]);

Route::post('/get-member-info', [
    'uses' => 'AdminController@getMemberInfo',
    'as' => 'get-member-info'
]);

Route::post('/get-member-matching-entries', [
    'uses' => 'AdminController@getMemberMatchingEntries',
    'as' => 'get-member-matching-entries'
]);

Route::post('/get-member-accumulated-purchases', [
    'uses' => 'AdminController@getMemberAccumulatedPurchases',
    'as' => 'get-member-accumulated-purchases'
]);

Route::post('/get-member-temp-password', [
    'uses' => 'AdminController@getMemberTempPassword',
    'as' => 'get-member-temp-password'
]);

Route::post('/do-save-member-entry', [
    'uses' => 'AdminController@doSaveMemberEntry',
    'as' => 'do-save-member-entry'
]);

Route::post('/do-transfer-member-position', [
    'uses' => 'AdminController@doTransferMemberPosition',
    'as' => 'do-transfer-member-position'
]);

Route::post('/do-upgrade-member-entry', [
    'uses' => 'AdminController@doUpgradeMemberEntry',
    'as' => 'do-upgrade-member-entry'
]);

//MEMBER VOUCHER  =======================================================================================================================================
Route::get('/admin-member-voucher', [
    'uses' => 'AdminController@showMemberVoucher',
    'as' => 'admin-member-voucher'
]);

Route::post('/get-member-voucher-list', [
    'uses' => 'AdminController@getMemberVoucherList',
    'as' => 'get-member-voucher-list'
]);

Route::post('/get-center-voucher-list', [
    'uses' => 'AdminController@getCenterVoucherList',
    'as' => 'get-center-voucher-list'
]);

Route::post('/get-member-voucher-info', [
    'uses' => 'AdminController@getMemberVoucherInfo',
    'as' => 'get-member-voucher-info'
]);

//PACKAGE  =======================================================================================================================================
Route::get('/admin-package-management', [
    'uses' => 'AdminController@showPackageManagement',
    'as' => 'admin-package-management'
]);

Route::post('/get-package-list', [
    'uses' => 'AdminController@getPackageList',
    'as' => 'get-package-list'
]);

Route::post('/get-package-info', [
    'uses' => 'AdminController@getPackageInfo',
    'as' => 'get-package-info'
]);

Route::post('/do-save-package', [
    'uses' => 'AdminController@doSavePackage',
    'as' => 'do-save-package'
]);

//PRODUCT  =======================================================================================================================================
Route::get('/admin-product-management', [
    'uses' => 'AdminController@showProductManagement',
    'as' => 'admin-product-management'
]);

Route::post('/get-product-list', [
    'uses' => 'AdminController@getProductList',
    'as' => 'get-product-list'
]);

Route::post('/get-product-info', [
    'uses' => 'AdminController@getProductInfo',
    'as' => 'get-product-info'
]);

Route::post('/do-save-product', [
    'uses' => 'AdminController@doSaveProduct',
    'as' => 'do-save-product'
]);

Route::post('/do-upload-product-photo', [
    'uses' => 'AdminController@doUploadProductPhoto',
    'as' => 'do-upload-product-photo'
]);

//INVENTORY LIST  =======================================================================================================================================
Route::get('/admin-inventory-list', [
    'uses' => 'AdminController@showInventoryList',
    'as' => 'admin-inventory-list'
]);

Route::post('/get-inventory-list', [
    'uses' => 'AdminController@getInventoryList',
    'as' => 'get-inventory-list'
]);

Route::post('/get-inventory-ledger', [
    'uses' => 'AdminController@getInventoryLedger',
    'as' => 'get-inventory-ledger'
]);

Route::post('/do-set-beginning-balance', [
    'uses' => 'AdminController@setInventoryBegBal',
    'as' => 'do-set-beginning-balance'
]);

Route::post('/do-set-min-max', [
    'uses' => 'AdminController@setInventoryMinMax',
    'as' => 'do-set-min-max'
]);

//INVENTORY ADJUSTMENT  =======================================================================================================================================
Route::get('/admin-inventory-adjustment', [
    'uses' => 'AdminController@showInventoryAdjustment',
    'as' => 'admin-inventory-adjustment'
]);

Route::post('/get-inventory-adjustment-list', [
    'uses' => 'AdminController@getInventoryAdjustmentList',
    'as' => 'get-inventory-adjustment-list'
]);

Route::post('/get-inventory-adjustment-info', [
    'uses' => 'AdminController@getInventoryAdjustmentInfo',
    'as' => 'get-inventory-adjustment-info'
]);

Route::post('/get-inventory-adjustment-item-list', [
    'uses' => 'AdminController@getInventoryAdjustmentItemList',
    'as' => 'get-inventory-adjustment-item-list'
]);

Route::post('/do-save-inventory-adjustment', [
    'uses' => 'AdminController@doSaveInventoryAdjustment',
    'as' => 'do-save-inventory-adjustment'
]);

Route::post('/do-cancel-inventory-adjustment', [
    'uses' => 'AdminController@doCancelInventoryAdjustment',
    'as' => 'do-cancel-inventory-adjustment'
]);

//PURCHASE ORDER  =======================================================================================================================================
Route::get('/admin-purchase-order', [
    'uses' => 'AdminController@showPurchaseOrder',
    'as' => 'admin-purchase-order'
]);

Route::post('/get-purchase-order-list', [
    'uses' => 'AdminController@getPOList',
    'as' => 'get-purchase-order-list'
]);

Route::get('/get-purchase-order-search-list', [
    'uses' => 'AdminController@getPOSearchList',
    'as' => 'get-purchase-order-search-list'
]);

Route::post('/get-purchase-order-info', [
    'uses' => 'AdminController@getPOInfo',
    'as' => 'get-purchase-order-info'
]);

Route::post('/get-purchase-order-item-list', [
    'uses' => 'AdminController@getPOItemList',
    'as' => 'get-purchase-order-item-list'
]);

Route::post('/get-purchase-order-voucher-list', [
    'uses' => 'AdminController@getPOVoucherList',
    'as' => 'get-purchase-order-voucher-list'
]);

Route::post('/do-save-purchase-order', [
    'uses' => 'AdminController@doSavePO',
    'as' => 'do-save-purchase-order'
]);

Route::post('/do-cancel-purchase-order', [
    'uses' => 'AdminController@doCancelPO',
    'as' => 'do-cancel-purchase-order'
]);

//PURCHASE ORDER - PROCESSING  =======================================================================================================================================
Route::get('/admin-po-processing', [
    'uses' => 'AdminController@showPOProcessing',
    'as' => 'admin-po-processing'
]);

Route::post('/get-po-processing-list', [
    'uses' => 'AdminController@getPOProcessingList',
    'as' => 'get-po-processing-list'
]);

Route::get('/get-po-processing-search-list', [
    'uses' => 'AdminController@getPOProcessingSearchList',
    'as' => 'get-po-processing-search-list'
]);

Route::post('/get-po-processing-info', [
    'uses' => 'AdminController@getPOProcessingInfo',
    'as' => 'get-po-processing-info'
]);

Route::post('/get-po-processing-item-list', [
    'uses' => 'AdminController@getPOProcessingItemList',
    'as' => 'get-po-processing-item-list'
]);

Route::post('/get-po-processing-voucher-list', [
    'uses' => 'AdminController@getPOProcessingVoucherList',
    'as' => 'get-po-processing-voucher-list'
]);

Route::post('/do-save-po-processing', [
    'uses' => 'AdminController@doSavePOProcessing',
    'as' => 'do-save-po-processing'
]);

Route::post('/do-cancel-po-processing', [
    'uses' => 'AdminController@doCancelPOProcessing',
    'as' => 'do-cancel-po-processing'
]);

//PURCHASE RECEIVE  =======================================================================================================================================
Route::get('/admin-purchase-receive', [
    'uses' => 'AdminController@showPurchaseReceive',
    'as' => 'admin-purchase-receive'
]);

Route::post('/get-purchase-receive-list', [
    'uses' => 'AdminController@getPurchaseReceiveList',
    'as' => 'get-purchase-receive-list'
]);

Route::post('/get-purchase-receive-info', [
    'uses' => 'AdminController@getPurchaseReceiveInfo',
    'as' => 'get-purchase-receive-info'
]);

Route::post('/get-purchase-receive-item-list', [
    'uses' => 'AdminController@getPurchaseReceiveItemList',
    'as' => 'get-purchase-receive-item-list'
]);

Route::post('/do-save-purchase-receive', [
    'uses' => 'AdminController@doSavePurchaseReceive',
    'as' => 'do-save-purchase-receive'
]);

Route::post('/do-cancel-purchase-receive', [
    'uses' => 'AdminController@doCancelPurchaseReceive',
    'as' => 'do-cancel-purchase-receive'
]);

//ORDER HISTORY  =======================================================================================================================================
Route::get('/admin-order-history', [
    'uses' => 'AdminController@showOrderHistory',
    'as' => 'admin-order-history'
]);

Route::get('/admin-order-unverified', [
    'uses' => 'AdminController@showOrderUnverified',
    'as' => 'admin-order-unverified'
]);

Route::get('/admin-order-verified', [
    'uses' => 'AdminController@showOrderVerified',
    'as' => 'admin-order-verified'
]);

Route::get('/admin-order-packed', [
    'uses' => 'AdminController@showOrderPacked',
    'as' => 'admin-order-packed'
]);

Route::get('/admin-order-shipped', [
    'uses' => 'AdminController@showOrderShipped',
    'as' => 'admin-order-shipped'
]);

Route::get('/admin-order-delivered', [
    'uses' => 'AdminController@showOrderDelivered',
    'as' => 'admin-order-delivered'
]);

Route::get('/admin-order-returned', [
    'uses' => 'AdminController@showOrderReturned',
    'as' => 'admin-order-returned'
]);

Route::get('/admin-order-cancelled', [
    'uses' => 'AdminController@showOrderCancelled',
    'as' => 'admin-order-cancelled'
]);

Route::get('/admin-order-uncollected', [
    'uses' => 'AdminController@showOrderUnCollected',
    'as' => 'admin-order-uncollected'
]);

Route::get('/admin-order-collected', [
    'uses' => 'AdminController@showOrderCollected',
    'as' => 'admin-order-collected'
]);

Route::post('/get-order-list', [
    'uses' => 'AdminController@getOrderList',
    'as' => 'get-order-list'
]);

Route::post('/get-order-info', [
    'uses' => 'AdminController@getOrderInfo',
    'as' => 'get-order-info'
]);

Route::post('/get-order-item-list', [
    'uses' => 'AdminController@getOrderItemList',
    'as' => 'get-order-item-list'
]);

Route::post('/get-order-voucher-list', [
    'uses' => 'AdminController@getOrdervoucherList',
    'as' => 'get-order-voucher-list'
]);

Route::post('/do-save-order', [
    'uses' => 'AdminController@doSaveOrder',
    'as' => 'do-save-order'
]);

Route::post('/do-paid-order', [
    'uses' => 'AdminController@doPaidOrder',
    'as' => 'do-paid-order'
]);

Route::post('/do-cancel-order', [
    'uses' => 'AdminController@doCancelOrder',
    'as' => 'do-cancel-order'
]);

Route::get('/admin-order-print', [
    'uses' => 'AdminController@showOrderPrint',
    'as' => 'admin-order-print'
]);

Route::post('/do-verify-order', [
    'uses' => 'AdminController@doVerifyOrder',
    'as' => 'do-verify-order'
]);

Route::post('/do-packed-order', [
    'uses' => 'AdminController@doPackedOrder',
    'as' => 'do-packed-order'
]);

Route::post('/do-shipped-order', [
    'uses' => 'AdminController@doShippedOrder',
    'as' => 'do-shipped-order'
]);

Route::post('/set-as-delivered-order', [
    'uses' => 'AdminController@doSetAsDeliveredOrder',
    'as' => 'set-as-delivered-order'
]);

//SHIPPER - J&T  =======================================================================================================================================
Route::get('/admin-shipper-jat', [
    'uses' => 'AdminController@showShipperJAT',
    'as' => 'admin-shipper-jat'
]);

Route::post('/get-shipper-jat-bracket', [
    'uses' => 'AdminController@getShipperJATBracketList',
    'as' => 'get-shipper-jat-bracket'
]);

Route::post('/get-shipper-jat-bracket-info', [
    'uses' => 'AdminController@getShipperJATBracketInfo',
    'as' => 'get-shipper-jat-bracket-info'
]);

Route::post('/do-save-shipper-jat-bracket', [
    'uses' => 'AdminController@doSaveShipperJATBracket',
    'as' => 'do-save-shipper-jat-bracket'
]);

//E-WALLET   =======================================================================================================================================
Route::get('/admin-member-ewallet', [
    'uses' => 'AdminController@showMemberEWallet',
    'as' => 'admin-member-ewallet'
]);

Route::post('/get-member-ewallet-ledger', [
    'uses' => 'AdminController@getMemberEwalletLedger',
    'as' => 'get-member-ewallet-ledger'
]);

Route::post('/get-member-ewallet-balance', [
    'uses' => 'AdminController@getMemberEwalletBalance',
    'as' => 'get-member-ewallet-balance'
]);

//EWALLET WITHDRAWAL  =======================================================================================================================================
Route::get('/admin-ewallet-withdrawal', [
    'uses' => 'AdminController@showEWalletWithdrawal',
    'as' => 'admin-ewallet-withdrawal'
]);

Route::post('/get-ewallet-withdrawal-list', [
    'uses' => 'AdminController@getEWalletWithdrawalList',
    'as' => 'get-ewallet-withdrawal-list'
]);

Route::post('/get-ewallet-withdrawal-info', [
    'uses' => 'AdminController@getEWalletWithdrawalInfo',
    'as' => 'get-ewallet-withdrawal-info'
]);

Route::post('/do-save-ewallet-withdrawal', [
    'uses' => 'AdminController@doSaveEWalletWithdrawal',
    'as' => 'do-save-ewallet-withdrawal'
]);

Route::post('/do-cancel-ewallet-withdrawal', [
    'uses' => 'AdminController@doCancelEWalletWithdrawal',
    'as' => 'do-cancel-ewallet-withdrawal'
]);

//REPORTS  =======================================================================================================================================
Route::get('/admin-sales-report', [
    'uses' => 'AdminController@showSalesReport',
    'as' => 'admin-sales-report'
]);

Route::post('/get-center-sales-report', [
    'uses' => 'AdminController@getCenterSalesReport',
    'as' => 'get-center-sales-report'
]);

Route::post('/get-commission-report', [
    'uses' => 'AdminController@getCommissionReport',
    'as' => 'get-commission-report'
]);

Route::get('/admin-commission-report', [
    'uses' => 'AdminController@showCommissionReport',
    'as' => 'admin-commission-report'
]);

Route::post('/get-withdrawal-report', [
    'uses' => 'AdminController@getWithdrawalReport',
    'as' => 'get-withdrawal-report'
]);

Route::get('/admin-withdrawal-report', [
    'uses' => 'AdminController@showWithdrawalReport',
    'as' => 'admin-withdrawal-report'
]);

Route::get('/admin-top-sponsorship-report', [
    'uses' => 'AdminController@showTopSponsorshipReport',
    'as' => 'admin-top-sponsorship-report'
]);

Route::post('/get-sponsorship-report', [
    'uses' => 'AdminController@getSponsorshipReport',
    'as' => 'get-sponsorship-report'
]);

Route::get('/admin-top-direct-selling-report', [
    'uses' => 'AdminController@showTopDirectSellingReport',
    'as' => 'admin-top-direct-selling-report'
]);

Route::post('/get-direct-selling-report', [
    'uses' => 'AdminController@getDirectSellingReport',
    'as' => 'get-direct-selling-report'
]);

Route::get('/admin-center-sales-report', [
    'uses' => 'AdminController@showCenterSalesReport',
    'as' => 'admin-center-sales-report'
]);

Route::get('/admin-top-network-builder-report', [
    'uses' => 'AdminController@showTopNetworkBuilderReport',
    'as' => 'admin-top-network-builder-report'
]);

//USER ACCOUNTS  =======================================================================================================================================
Route::get('/admin-user-accounts', [
    'uses' => 'AdminController@showUserAccounts',
    'as' => 'admin-user-accounts'
]);

Route::post('/get-user-accounts-list', [
    'uses' => 'AdminController@getUserAccountsList',
    'as' => 'get-user-accounts-list'
]);

Route::post('/get-user-accounts-info', [
    'uses' => 'AdminController@getUserAccountInfo',
    'as' => 'get-user-accounts-info'
]);

Route::post('/do-save-user-accounts', [
    'uses' => 'AdminController@doSaveUserAccount',
    'as' => 'do-save-user-accounts'
]);

//Company Information  =======================================================================================================================================
Route::get('/admin-company-info', [
    'uses' => 'AdminController@showCompanyInfo',
    'as' => 'admin-company-info'
]);

Route::post('/do-save-company-info', [
    'uses' => 'AdminController@doSaveCompanyInfo',
    'as' => 'do-save-company-info'
]);

//News and Events  =======================================================================================================================================
Route::get('/admin-news-and-events', [
    'uses' => 'AdminController@showNewsEvents',
    'as' => 'admin-news-and-events'
]);

Route::post('/get-news-events-list', [
    'uses' => 'AdminController@getNewsEventsList',
    'as' => 'get-news-events-list'
]);

Route::post('/get-news-events-info', [
    'uses' => 'AdminController@getNewsEventsInfo',
    'as' => 'get-news-events-info'
]);

Route::post('/do-save-news-events', [
    'uses' => 'AdminController@doSaveNewsEvents',
    'as' => 'do-save-news-events'
]);

//FAQ =======================================================================================================================================
Route::get('/admin-faq', [
    'uses' => 'AdminController@showFAQ',
    'as' => 'admin-faq'
]);

Route::post('/get-faq-list', [
    'uses' => 'AdminController@getFAQList',
    'as' => 'get-faq-list'
]);

Route::post('/get-faq-info', [
    'uses' => 'AdminController@getFAQInfo',
    'as' => 'get-faq-info'
]);

Route::post('/do-save-faq', [
    'uses' => 'AdminController@doSaveFAQ',
    'as' => 'do-save-faq'
]);

//CHANGE PASSWORD =======================================================================================================================================
Route::get('/admin-change-password', [
    'uses' => 'AdminController@showChangePassword',
    'as' => 'admin-change-password'
]);

Route::post('do-change-admin-password', [
    'uses' => 'AdminController@doChangePassword',
    'as' => 'do-change-admin-password'
]);

//MEMBER =======================================================================================================================================
Route::get('/member-login', [
    'uses' => 'MemberController@showMemberLogin',
    'as' => 'member-login'
]);

Route::get('/auto-login', [
    'uses' => 'MemberController@showMemberLogin2',
    'as' => 'auto-login'
]);


Route::post('/do-upload-member-photo', [
    'uses' => 'MemberController@doUploadMemberPhoto',
    'as' => 'do-upload-member-photo'
]);

Route::post('/do-member-login', [
    'uses' => 'MemberController@doMemberLogin',
    'as' => 'do-member-login'
]);



Route::get('/member-change-password', [
    'uses' => 'MemberController@showChangePassword',
    'as' => 'member-change-password'
]);

Route::post('/do-change-member-password', [
    'uses' => 'MemberController@doChangePassword',
    'as' => 'do-change-member-password'
]);

Route::get('/member-logout', [
    'uses' => 'MemberController@doMemberLogout',
    'as' => 'member-logout'
]);

Route::get('/member-dashboard', [
    'uses' => 'MemberController@showDashboard',
    'as' => 'member-dashboard'
]);

Route::get('/member-genealogy', [
    'uses' => 'MemberController@showMemberGenealogy',
    'as' => 'member-genealogy'
]);

Route::get('/member-profile', [
    'uses' => 'MemberController@showMemberProfile',
    'as' => 'member-profile'
]);

Route::get('/member-ewallet-ledger', [
    'uses' => 'MemberController@showMemberEWallet',
    'as' => 'member-ewallet-ledger'
]);

Route::get('/member-ewallet-withdrawal', [
    'uses' => 'MemberController@showEWalletWithdrawal',
    'as' => 'member-ewallet-withdrawal'
]);

Route::get('/member-upgrade-entry', [
    'uses' => 'MemberController@showMemberUpgradeEntry',
    'as' => 'member-upgrade-entry'
]);

Route::get('/member-order-history', [
    'uses' => 'MemberController@showMemberOrderHistory',
    'as' => 'member-order-history'
]);

Route::get('/member-vouchers', [
    'uses' => 'MemberController@showMemberVouchers',
    'as' => 'member-vouchers'
]);

Route::get('/member-tree', [
    'uses' => 'MemberController@showMemberTree',
    'as' => 'member-tree'
]);
//MAIL =======================================================================================================================================
Route::post('/do-forgot-password', [
    'uses' => 'MailController@doForgetPassword',
    'as' => 'do-forgot-password'
]);

//SYSTEM SETTINGS =======================================================================================================================================
Route::get('/admin-settings', [
    'uses' => 'AdminController@showSettings',
    'as' => 'admin-settings'
]);

Route::post('/do-save-settings', [
    'uses' => 'AdminController@doSaveSettings',
    'as' => 'do-save-settings'
]);

//CRON JOBS ============================================================================
Route::post('/success_formula_sfi_dhatz_cronjobs', [
    'uses' => 'AdminController@doScheduledJob',
    'as' => 'success_formula_sfi_dhatz_cronjobs'
]);
Route::get('/success_formula_sfi_dhatz_cronjobs', [
    'uses' => 'AdminController@doScheduledJob',
    'as' => 'success_formula_sfi_dhatz_cronjobs'
]);
//END CRON JOBS =============================================================
