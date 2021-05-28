<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\InventoryFilters\PriceFilter;
use App\Http\Filters\InventoryFilters\ProductNameFilter;
use App\Http\Filters\InventoryFilters\VendorNameFilter;
use App\Http\Resources\InventoryCollection;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Illuminate\Http\Request $request [product_name, vendor_name, price, sort= column, sort_type]
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // search is scope used for search in multiple column with oop style
            $inventories = Inventory::search($this->filters());

            if($request->filled('sort')) {
                $column    = explode(',', $request->sort)[0];
                $sort_type = explode(',', $request->sort)[1];
                $inventories = $inventories->orderBy($column, $sort_type);
            }

            $inventories = $inventories->paginate(10);
            return response()->json([
                'status' => true,
                'message' => 'return all inventories data according to search and sort variable' ,
                'data'    => new InventoryCollection($inventories)
                ], 201
            );
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'there are error' , 'data' => []], 400);
        }
    }


     /**
     * Method filters
     *
     * all availabe search value with custom search class
     *
     * @return array
     */
    public function filters()
    {
        return [
            'product_name' => new ProductNameFilter,
            'vendor_name'  => new VendorNameFilter,
            'price'        => new PriceFilter,
        ];
    }
}
