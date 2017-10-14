<?php
//Route::middleware(['auth'])->group(function () {

    require_once base_path('routes/risk.php');

    require_once "process.php";


    Route::get('/', 'HomeController@index')->name('home');


    Route::get('new-project', 'HomeController@newProject')->name('new_project');

    Route::post('store/new-project', 'HomeController@storeProject')->name('store_project');

    Route::get('risk-assessment', 'HomeController@riskAssessment')->name('risk_assessment');

    Route::get('process-hierarchy-view', 'HomeController@processHierarchyView')->name('process_hierarchy_view');

    Route::get('process-list-view', 'HomeController@processListView')->name('process_list_view');

    Route::get('logout', 'HomeController@logout')->name('logout');

    Route::post('store/process', 'HomeController@storeProcess')->name('store_process');



    Route::get('/home', 'HomeController@index')->name('home');


    Route::get('/send-email', 'EmailController@send')->name('send_email');


    /*
     *
     * Control Library Routes
     *
     */

    Route::get('/control-tree', 'ControlLibraryController@controlTree')->name('control_tree');

    Route::get('/control-actions/{process_domain_id}', 'ControlLibraryController@controlActions')->name('control_action');

    Route::get('edit/control/{control_id}', 'ControlLibraryController@editFormControl')->name('edit_control');

    Route::post('edit/control', 'ControlLibraryController@updateControl')->name('update_control');

    Route::post("save/custom-field", "ControlLibraryController@storeControlCustomField")->name("store_control_custom_field");

/*
    ****************************************************************************************************************************
*/

    Route::get("edit/tabs-menus", "HomeController@editTabsMenus")->name("edit_tabs_menus");

//});





Auth::routes();



