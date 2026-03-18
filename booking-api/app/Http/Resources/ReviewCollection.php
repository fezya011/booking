<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
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
                'average_rating' => $this->collection->avg('rating'),
                'ratings_distribution' => [
                    5 => $this->collection->where('rating', '>=', 4.5)->count(),
                    4 => $this->collection->whereBetween('rating', [3.5, 4.49])->count(),
                    3 => $this->collection->whereBetween('rating', [2.5, 3.49])->count(),
                    2 => $this->collection->whereBetween('rating', [1.5, 2.49])->count(),
                    1 => $this->collection->where('rating', '<', 1.5)->count(),
                ],
            ],
        ];
    }
}
