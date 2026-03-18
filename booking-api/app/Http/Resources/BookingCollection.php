<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
                'stats' => [
                    'total_spent' => $this->collection->sum('total_price'),
                    'upcoming' => $this->collection->where('status', 'confirmed')->count(),
                    'pending' => $this->collection->where('status', 'pending')->count(),
                    'completed' => $this->collection->where('status', 'completed')->count(),
                ],
            ],
        ];
    }
}
