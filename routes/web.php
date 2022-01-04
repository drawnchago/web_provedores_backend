<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('login', 'UserController@login');
$router->get('getUsers', 'UserController@getAll');

$router->get('getMenu', 'MainController@getMenu');
$router->post('testImg', 'ProcessController@testImg');



//! Ordenes de trabajo
$router->post('saveWorkOrder', 'ProcessController@saveWorkOrder');
$router->post('saveExitSheet', 'ProcessController@saveExitSheet');
$router->get('updateStatusOrders', 'ProcessController@updateStatusOrders');
$router->get('getWorkOrders', 'ProcessController@getAllWorkOrders');
$router->get('getPiecesByBombId', 'ProcessController@getPiecesByBombId');
$router->post('saveInspecionPiece', 'ProcessController@saveInspecionPiece');
$router->get('getPiecesInspection', 'ProcessController@getPiecesInspection');
$router->get('getOrdersEntry', 'ProcessController@getOrdersEntry');
$router->get('getExitSheet', 'ProcessController@getAllExitSheet');
$router->get('getOrdetSheets', 'ProcessController@getOrdetSheets');
$router->get('getCustomersActives', 'ProcessController@getCustomers');
$router->get('getBombsActives', 'ProcessController@getBombsActives');
$router->get('getModelsActives', 'ProcessController@getModelsActives');
$router->get('getBrandsActives', 'ProcessController@getBrandsActives');
$router->get('pdfEntry', 'ProcessController@getDataEntry');
$router->get('getOrderSheetByWorkOrderId', 'ProcessController@getOrdetSheetsByWorkOrderId');
$router->post('saveNewPiece', 'ProcessController@saveNewPiece');
/*CATALOGSCONTROLLER*/
//TYPESBOMB
$router->get('getTypesBomb', 'OpCatalogsController@getTypesBomb');
$router->post('saveTypeBomb', 'OpCatalogsController@saveTypeBomb');
$router->post('deleteTypeBomb', 'OpCatalogsController@deleteTypeBomb');
//BRANDS
$router->get('getBrandsBomb', 'OpCatalogsController@getBrandsBomb');
$router->post('saveBrandBomb', 'OpCatalogsController@saveBrandBomb');
$router->post('deleteBrandBomb', 'OpCatalogsController@deleteBrandBomb');
//MODELS
$router->get('getModelsBomb', 'OpCatalogsController@getModelsBomb');
$router->post('saveModelBomb', 'OpCatalogsController@saveModelBomb');
$router->post('deleteModelBomb', 'OpCatalogsController@deleteModelBomb');
//BANKS
$router->get('getBanks', 'OpCatalogsController@getBanks');
//KINDOFPERSONS
$router->get('getKindOfPersons', 'OpCatalogsController@getKindOfPersons');
$router->get('getCfdi', 'OpCatalogsController@getCfdi');
//CUSTOMERS
$router->get('getCustomers', 'OpCatalogsController@getCustomers');
$router->post('saveCustomer', 'OpCatalogsController@saveCustomer');
$router->post('deleteCustomer', 'OpCatalogsController@deleteCustomer');
/*WA-CONTROLLER*/
$router->get('getWarehouses', 'WaCatalogsController@getWarehouses');
$router->post('saveWarehouses', 'WaCatalogsController@saveWarehouses');
$router->post('deleteWarehouses', 'WaCatalogsController@deleteWarehouses');
/*PR-CONTROLLER*/
$router->get('getProducts', 'PrCatalogsController@getProducts');
$router->post('saveProduct', 'PrCatalogsController@saveProduct');
$router->post('deleteProduct', 'PrCatalogsController@deleteProduct');
/*UOM-CONTROLLER*/
$router->get('getMeasurementUnits', 'MuCatalogsController@getMeasurementUnits');
$router->post('saveMeasurementUnit', 'MuCatalogsController@saveMeasurementUnit');
$router->post('deleteMeasurementUnit', 'MuCatalogsController@deleteMeasurementUnit');
/*Tp-CONTROLLER*/
$router->get('getTypeOfProducts', 'TpCatalogsController@getTypeOfProducts');
$router->post('saveTypeOfProduct', 'TpCatalogsController@saveTypeOfProduct');
$router->post('deleteTypeOfProduct', 'TpCatalogsController@deleteTypeOfProduct');
/*Cl-CONTROLLER*/
$router->get('getClassifications', 'ClCatalogsController@getClassifications');
$router->post('saveClassifications', 'ClCatalogsController@saveClassifications');
$router->post('deleteClassifications', 'ClCatalogsController@deleteClassifications');
/*St-CONTROLLER  COUNTRIES*/
$router->get('getCountries', 'StCatalogsController@getCountries');
$router->post('saveCountry', 'StCatalogsController@saveCountry');
$router->post('deleteCountry', 'StCatalogsController@deleteCountry');
/*St-CONTROLLER    STATES*/
$router->get('getStates', 'StCatalogsController@getStates');
$router->post('saveState', 'StCatalogsController@saveState');
$router->post('deleteState', 'StCatalogsController@deleteState');
/*St-CONTROLLER    MUNICIPALITIES*/
$router->get('getMunicipalities', 'StCatalogsController@getMunicipalities');
$router->post('saveMunicipality', 'StCatalogsController@saveMunicipality');
$router->post('deleteMunicipality', 'StCatalogsController@deleteMunicipality');
/*St-CONTROLLER    AREAS*/
$router->get('getAreas', 'StCatalogsController@getAreas');
$router->post('saveArea', 'StCatalogsController@saveArea');
$router->post('deleteArea', 'StCatalogsController@deleteArea');
/*St-CONTROLLER    AREAS*/
$router->get('getAreas', 'StCatalogsController@getAreas');
$router->post('saveArea', 'StCatalogsController@saveArea');
$router->post('deleteArea', 'StCatalogsController@deleteArea');
/*St-CONTROLLER    AREAS*/
$router->get('getPaymentMethods', 'StCatalogsController@getPaymentMethods');
$router->post('savePaymentMethod', 'StCatalogsController@savePaymentMethod');
$router->post('deletePaymentMethod', 'StCatalogsController@deletePaymentMethod');
/*St-CONTROLLER    COINS*/
$router->get('getCoins', 'StCatalogsController@getCoins');
$router->post('saveCoin', 'StCatalogsController@saveCoin');
$router->post('deleteCoin', 'StCatalogsController@deleteCoin');
/*Sh-CONTROLLER    PROVIDERS*/
$router->get('getProviders', 'ShCatalogsController@getProviders');
$router->post('saveProvider', 'ShCatalogsController@saveProvider');
$router->post('deleteProvider', 'ShCatalogsController@deleteProvider');
/*Sh-CONTROLLER    CFDI*/
$router->get('getCfdi', 'ShCatalogsController@getCfdi');
$router->post('saveCfdi', 'ShCatalogsController@saveCfdi');
$router->post('deleteCfdi', 'ShCatalogsController@deleteCfdi');
/*Sh-CONTROLLER    BANKS*/
$router->get('getBanks', 'ShCatalogsController@getBanks');
$router->post('saveBank', 'ShCatalogsController@saveBank');
$router->post('deleteBank', 'ShCatalogsController@deleteBank');
/*Sh-CONTROLLER   KINDOFPERSONS*/
$router->get('getKindOfPersons', 'ShCatalogsController@getKindOfPersons');
$router->post('saveKindOfPerson', 'ShCatalogsController@saveKindOfPerson');
$router->post('deleteKindOfPerson', 'ShCatalogsController@deleteKindOfPerson');
/*Sh-CONTROLLER   KINDOFPERSONS*/
$router->get('getCommercialBusiness', 'ShCatalogsController@getCommercialBusiness');
$router->post('saveCommercialBusiness', 'ShCatalogsController@saveCommercialBusiness');
$router->post('deleteCommercialBusiness', 'ShCatalogsController@deleteCommercialBusiness');
/*CREATEPDFCONTROLLER*/
$router->get('getBranchOffice', 'CreatePDFController@getBranchOffice');
/*INVOICING*/
$router->get('getOrdersToInvoice','AdmInvoicingController@getWorkOrders');
$router->get('getXML','AdmInvoicingController@getXML');
$router->post('stamp','AdmInvoicingController@stamp');
/*PURCHASE CONTROLLER*/
$router->post('addProvider', 'ShProcessController@addProvider');
$router->post('approveOrDeny', 'ShProcessController@approveOrDenyRequisition');
$router->post('authorizeOrDeny', 'ShProcessController@authorizeOrDeny');
$router->get('getPurchaseOrdersDetails', 'ShProcessController@getPurchaseOrdersDetails');
$router->get('getPurchaseRequsitions', 'ShProcessController@getPurchaseRequsitions');
$router->get('getRequisitionDetails', 'ShProcessController@getRequisitionDetails');
$router->get('getPurchaseRequsitionsByArea', 'ShProcessController@getPurchaseRequsitionsByArea');
$router->get('getPurchaseOrders', 'ShProcessController@getPurchaseOrders');
$router->post('savePurchaseRequsition', 'ShProcessController@savePurchaseRequsition');
$router->get('getConversation', 'ShProcessController@getConversation');
$router->post('saveConversation', 'ShProcessController@saveConversation');
$router->post('deletePurchaseRequsition', 'ShProcessController@deletePurchaseRequsition');
// $router->get('getMunicipalitiesByType', 'MainController@getMunicipalitiesByType');
/*MAINCONTROLLER*/
$router->get('getStatesByType', 'MainController@getStatesByType');
$router->get('getMunicipalitiesByType', 'MainController@getMunicipalitiesByType');
$router->get('getAreasByType', 'MainController@getAreasByType');
$router->get('getUsersByType', 'MainController@getUsersByType');
$router->get('getProductsByType', 'MainController@getProductsByType');
$router->get('getBranchOffices', 'MainController@getBranchOffices');
$router->get('getCustomerByDescription', 'MainController@getCustomerByDescription');
$router->get('getTasaIVA', 'MainController@getTasaIVA');
/*SALESPRCONTROLLER */
$router->get('getQuotations', 'SalesProcessController@getQuotations');
$router->get('getLastFolioQuotation', 'SalesProcessController@getLastFolioQuotation');
$router->get('getWorkOrdersNotAssigned', 'SalesProcessController@getWorkOrdersNotAssigned');
$router->post('saveQuotation', 'SalesProcessController@saveQuotation');
$router->post('deleteQuotation', 'SalesProcessController@deleteQuotation');
