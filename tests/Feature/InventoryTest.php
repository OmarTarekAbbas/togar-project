<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

     //test only ProductName
    public function testReturnOnlyWithProductName()
    {
        $response = $this->get('api/inventories?product_name=Garfield');
        $response->assertStatus(201);
    }

    //test only VendorName
    public function testReturnOnlyWithVendorName()
    {
        $response = $this->get('api/inventories?vendor_name=Matt');
        $response->assertStatus(201);
    }
    
    //test only Price
    public function testReturnOnlyWithPrice()
    {
        $response = $this->get('api/inventories?price=363.96');
        $response->assertStatus(201);
    }


    //test only sort
    public function testReturnOnlysort()
    {
        $response = $this->get('api/inventories?sort=price,asc');
        $response->assertStatus(201);
    }

    /**
     * A basic test example.
     *
     * @return void
     */

     //test All parameter Api
    public function testReturnAllInventoriesDataAccordingToSearchAndSortVariablet()
    {
        $response = $this->get('api/inventories?product_name=mr&vendor_name=mis&price=476.54&sort=price,asc');
        $response->assertStatus(201);
    }
}
