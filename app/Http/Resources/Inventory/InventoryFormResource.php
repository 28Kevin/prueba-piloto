<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'type' => 'inventory',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->product->name,
                'quantity' => $this->quantity,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }

}
