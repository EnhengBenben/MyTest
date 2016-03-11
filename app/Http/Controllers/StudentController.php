<?php
/**
 * Created by PhpStorm.
 * User: zgc
 * Date: 2015/7/22
 * Time: 10:07
 */
namespace App\Http\Controllers;

use App\Datatables\Datatables;
use App\Http\Requests;

use App\Models\Course;
use App\Support\Datatables_new;
use App\Support\ExportHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    public function index()
    {
        $courses = Course::whereNotNull("published_at")->get(['id','name']);
        $id = '';
        return view('student.index',['courses'=>$courses,'id'=>$id]);
    }

    public function indexDt(){
        $baseQuery = $this->getIndexQuery();
        //time filter
        if(\Input::get("announcement")){
            $baseQuery->where('announcement_date',">=",\Input::get("announcement").'-01-01')->where('announcement_date','<=',\Input::get("announcement").'-12-31');
        }
        //course filter
        if(\Input::get("course_id")){
            //$baseQuery->where('course.name',"like","%".\Input::get("course")."%");
            $baseQuery->where("course.id","=",\Input::get("course_id"));
        }
        $baseQuery->select(['application.id', 'application.name', 'phone_number','organization','tech_duty.name as tech_duty','course.name as course']);
        $dto = Datatables_new::makeDto($baseQuery);
        return $dto;
    }
    public function indexExport()
    {
        \Input::merge(json_decode(\Input::get('json'), true));
        $baseQuery = $this->getIndexQuery();
        //time filter
        //dd(\Input::get("announcement"));
        if(\Input::get("announcement")){
            $baseQuery->where('announcement_date',">=",\Input::get("announcement").'-01-01')->where('announcement_date','<=',\Input::get("announcement").'-12-31');
            //dd(\Input::get("announcement").'-01-01');
        }
        //course filter
        if(\Input::get("course_id")){
            $baseQuery->where('course.id',"=",\Input::get("course_id"));
        }
        $query = Datatables_new::makeQuery($baseQuery);
        $query->select(
            ['application.name', 'application.phone_number','organization','tech_duty.name as tech_duty','course.name as course']);
        ExportHelper::exportFromQuery($query, "通讯录", [['姓名', '手机号','单位','职称','进修班']]);
    }

    private function getIndexQuery()
    {
        $query = \DB::table('application')
            ->rightJoin('application_course_result','application_course_result.application_id','=','application.id')
            ->where('confirm', 1)
            ->leftJoin('course', function ($join) {
                $join->on('course.id', '=', 'course_id')->whereNotNull('passed_at')
                    ->orOn('postpone_course_id', '=', 'course.id')->whereNotNull('postpone_course_id')
                    ->orOn('transfer_course_id', '=', 'course.id')->whereNotNull('transfer_course_id');
                })
          //  })'application_course_result.course_id','=','course.id')->
           // ->LeftJoin('course','application_course_result.postpone_course_id','=','course.id')->orwhereNotNull('postpone_course_id')
           // ->LeftJoin('course','application_course_result.transfer_course_id','=','course.id')->orwhereNotNull('transfer_course_id')
            ->leftJoin('tech_duty','application.tech_duty_id','=','tech_duty.id');
        return $query;
    }

}