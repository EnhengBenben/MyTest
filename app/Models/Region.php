<?php

namespace App\Models;


class Region extends BaseModel
{
    //
    protected $table = 'region';

    public function application(){
        return $this->hasMany(Application::class);
}
}
