<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\InventoryResource;

class InventoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'inventories' => InventoryResource::collection($this->collection),
          'count'        => $this->count(),
          'current_page'  => $this->currentPage(),
          'first_item'    => $this->firstItem(),
          'has_more_pages' => $this->hasMorePages(),
          'last_item'     => $this->lastItem(),
          'last_page'     => $this->lastPage(),
          'next_page_url'  => $this->nextPageUrl(),
          'per_page'      => $this->perPage(),
          'previous_page_url' => $this->previousPageUrl(),
          'total'      => $this->total(),
          'total_pages' => $this->lastPage(),
        ];
    }
}
