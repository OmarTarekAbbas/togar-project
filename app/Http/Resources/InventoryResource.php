<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
     return [
          'id' => $this->id,
          'product_name' => $this->product_name,
          'vendor_name' => $this->vendor_name,
          'price' => $this->price,
          'most_selling' => $this->most_selling,
          'rate' => $this->rate,
      ];

    }
}
