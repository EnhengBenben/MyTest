<?php

namespace App\Models;




class Course extends BaseModel
{
    //
    protected $table = 'course';

    public function application(){
        return $this->belongsToMany(Application::class,'application_course_result')
            ->withPivot('rejected_at','passed_at','confirm','confirmed_at','transfer_course_id','postpone_course_id','submitted_at');
    }
    public function attachment(){
        return $this->hasMany(Attachment::class);
    }
}
