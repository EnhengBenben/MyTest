<?php

namespace App\Models;




class Attachment extends BaseModel
{
    //
    protected $table = 'attachment';

    public function course(){
        return $this->belongsTo(Course::class);
}
}
