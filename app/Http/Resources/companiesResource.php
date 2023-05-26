<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class companiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'establecimiento' => $this->establecimiento,
            'tipo' => $this->tipo,
            'via' => $this->via,
            'direccion' => $this->direccion,
            'piso' => $this->piso,
            'cp' => $this->cp,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'coordx' => $this->coordx,
            'coordy' => $this->coordy,
        ];
    }
}
