<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'token' => $this->token,
        ];
    }
}
