<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class StreamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'channel_name' => $this->channel_name,
            'title' => Str::limit($this->title ?? 'Untitled Stream', 100),
            'game_category' => $this->game_category ?? 'No Category',
            'average_viewers' => $this->average_viewers ?? 0,
            'started_at' => $this->started_at?->toISOString(),
            'partner_id' => $this->partner_id,
        ];
    }
}
