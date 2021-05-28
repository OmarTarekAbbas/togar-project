<?php

use App\RoleRoute;
use App\DeleteAll;
use App\Route as RouteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

function delete_multiselect(Request $request) // select many contract from index table and delete them
{
    $selected_list =  explode(",", $request['selected_list']);
    foreach ($selected_list as $item) {
        DB::table($request['table_name'])->where('id', $item)->delete();
    }
    \Session::flash('success', \Lang::get('messages.custom-messages.deleted'));
}

function restore($table_name, $record_id)
{
    \DB::table($table_name)->where('id', $record_id)->update(['rectype_id' => 2]);
}

function get_delete_all_flag()
{
    $route = \Route::getFacadeRoot()->current()->uri();
    $get_route = RouteModel::where('route', $route)->where('method', 'get')->first();
    $flag = $get_route->delete_all_model;
    if ($flag)
        return true;
    return false;
}

function get_static_routes()
{

    Auth::routes([
        'register' => false,
    ]);

    Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);
    //front
    Route::get('/', 'FrontController@index');
    Route::get('/index', 'FrontController@index_mobile');
    Route::get('home', 'FrontController@home');
    Route::get('list_services/{id}', 'FrontController@services');
    Route::get('list_contents/{id}', 'FrontController@contents');
    Route::get('view_content/{id}', 'FrontController@view_content');
    Route::get('sebha', 'FrontController@sebha');
    Route::get('zakah', 'FrontController@zakah');
    Route::get('merath', 'FrontController@merath');
    Route::get('merath_calc', 'FrontController@merath_calc');
    Route::get('salah_time', 'FrontController@salah_time');
    Route::get('mosque', 'FrontController@mosque');
    Route::get('view_content', 'FrontController@op_id');
    Route::get('view_audio', 'FrontController@op_id_au');
    // Route::get('view_content?op_id={id}', function(

    // );
    // Route::get('azan', 'FrontController@azan');
    // Route::get('providers/list_azan', 'FrontController@list_azan');
    // Route::get('view_rbt/{id}', 'FrontController@view_rbt');
    // Route::get('rbts', 'FrontController@rbts');



    // Route::get('/', 'DashboardController@index');
    // Route::get('/home', 'DashboardController@index');

    Route::group(['middleware' => 'auth'], function () {
        Route::resource('static_translation', '\App\Http\Controllers\StaticTranslationController');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('routes_v2', 'RouteController@create_v2');
        Route::get('routes/index_v2', 'RouteController@index_v2');
        Route::get('get_controller_methods', 'RouteController@get_methods_for_selected_controller');
        Route::post('routes/store_v2', 'RouteController@store_v2');
        Route::get('JIC/index', 'HomeController@JICindex');

        Route::get('ldap', 'DashboardController@ldap');
        Route::get('pages', 'SettingController@page_index');
        Route::get('export_DB', 'DashboardController@export_DB_backup');
        Route::get('database_backups', 'DashboardController@list_backups');
        Route::get('delete_backup', 'DashboardController@delete_backup');
        Route::get('import_DB', 'DashboardController@import_DB_backup');
        Route::get('download_backup', 'DashboardController@download_backup');
        Route::get('/clear-cache', 'DashboardController@clear_cache');
        Route::get('admin/elfinder', 'ElfinderController@getIndex');
        Route::post('admin/elfinder', 'ElfinderController@getIndex');
        Route::get('admin/seed_manager', 'DashboardController@seed_manager');
        Route::post('admin/seed_tables', 'DashboardController@seed_tables');
        Route::get('admin/migrate_manager', 'DashboardController@migrate_manager');
        Route::post('admin/migrate_tables', 'DashboardController@migrate_tables');
        Route::get('content/allData', 'ContentController@allData');
        Route::get('post/allData', 'PostController@allData');
        // Route::resource('provider', 'ProviderController');
    });


    Route::post('delete_multiselect', function (Request $request) {
        if (strlen($request['selected_list']) == 0) {
            \Session::flash('failed', \Lang::get('messages.custom-messages.no_selected_item'));
            return back();
        }
        delete_multiselect($request);
        return back();
    });
    Route::get('get_table_ids', 'DashboardController@get_table_ids_list');
}

function get_dynamic_routes()
{
    $route = \Request::url();
    $request_method = strtolower(\Request::method());
    $action = "";
    $checker = false;
    $url_to = \URL::to('');
    $start_from = strpos($route, $url_to);
    for ($i = strlen($url_to) + 1; $i < strlen($route); $i++) {
        // ex : url = http://localhost/ivas_template_v2/users => so i want to skip all before users
        if (is_numeric($route[$i])) {
            if (!$checker) {
                if ($route[$i - 1] == "/") {
                    // it may be a route with name index_v2,without this validation it will be index_v{id}
                    $action .= "{id}";
                    // for the edit request , language/9/edit => language/{id}/edit
                    $checker = true;
                } else
                    $action .= $route[$i];
            } else
                continue;
        } else {
            $action .= $route[$i];
        }
    }
    try {
        $query = "SELECT * FROM routes
                      JOIN role_route ON routes.id = role_route.route_id
                      JOIN roles ON role_route.role_id = roles.id
                      WHERE routes.route = '" . $action . "' AND routes.method='" . $request_method . "'";
        $route_model = \DB::select($query);
        if (count($route_model) > 0) {
            dynamic_routes($route_model, true);
        } else {
            $query_2 = "SELECT * FROM routes
                            WHERE routes.route = '" . $action . "'
                            AND routes.method='" . $request_method . "'";
            $route_model = \DB::select($query_2);
            dynamic_routes($route_model, false);
        }
    } catch (Illuminate\Database\QueryException $e) {
    }
}

function dynamic_routes($route_model, $found_roles)
{
    $roles = "";
    if (count($route_model) == 0) {
        return;
    }
    $route = $route_model[0]->route;
    $controller_method =
        $route_model[0]->controller_name . "@" . $route_model[0]->function_name;
    $route_method = $route_model[0]->method;
    if ($found_roles) {
        for ($i = 0; $i < count($route_model); $i++) {
            $roles .= $route_model[$i]->name;
            if ($i < count($route_model) - 1)
                $roles .= "|";
        }
        Route::group(
            ['middleware' => ['auth']],
            function () use ($route_model, $route_method, $route, $controller_method) {
                if ($route_method == "resource")
                    Route::resource($route, $controller_method);
                else if ($route_method == "get")
                    Route::get($route, $controller_method);
                else if ($route_method == "post")
                    Route::post($route, $controller_method);
                else if ($route_method == "put")
                    Route::put($route, $controller_method);
                else if ($route_method == "patch")
                    Route::patch($route, $controller_method);
                else if ($route_method == "delete")
                    Route::delete($route, $controller_method);
            }
        );
    } else {
        Route::group(
            ['middleware' => ['auth']],
            function () use ($route_model, $route_method, $route, $controller_method) {
                if ($route_method == "resource")
                    Route::resource($route, $controller_method);
                else if ($route_method == "get")
                    Route::get($route, $controller_method);
                else if ($route_method == "post")
                    Route::post($route, $controller_method);
                else if ($route_method == "put")
                    Route::put($route, $controller_method);
                else if ($route_method == "patch")
                    Route::patch($route, $controller_method);
                else if ($route_method == "delete")
                    Route::delete($route, $controller_method);
            }
        );
    }
}

function get_action_icons($route, $method)
{

    // check user is login and hass role
    $userRole = Auth::user()->roles->first()->id;
    if ($userRole == 1) {
        return true;
    }
    if ($userRole) {
        // check route
        $route = RouteModel::where('route', $route)->where('method', $method)->first();
    }
    if ($route) {
        // chec user roles has access this route
        $routeRole = RoleRoute::where('role_id', $userRole)->where('route_id',  $route->id)->first();
        return $routeRole || $userRole == 1 ? 1 : 0;
    }
    return false;
}
