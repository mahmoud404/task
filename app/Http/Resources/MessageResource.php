<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'content'=>$this->content,
            'user'=>AccountResource::make($this->user),
            'created_at'=>$this->created_at->isoFormat('LLL'),
        ];
    }
}
