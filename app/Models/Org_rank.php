<?php

namespace App\Models;




class Org_rank extends BaseModel
{
    //
    protected $table = 'org_rank';

    public function application(){
        return $this->hasMany(Application::class);
}
}
