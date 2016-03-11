<?php

namespace App\Models;




class Tech_duty extends BaseModel
{
    //
    protected $table = 'tech_duty';

    public function application(){
        return $this->hasMany(Application::class);
}
}
