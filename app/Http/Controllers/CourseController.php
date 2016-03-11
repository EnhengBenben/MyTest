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

use App\Models\Attachment;
use App\Models\Course;
use App\Support\Datatables_new;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Application;

class CourseController extends Controller
{
    public function index()
    {
        return view('course.index');
    }

    public function indexDt(){
        $baseQuery = \DB::table('course')->whereNotNull('published_at');
        //time filter
        //dd(\Input::get("start_date"));
        if(\Input::get("start_date")){
            $baseQuery->where('application_deadline',">=",\Input::get("start_date").' 00:00:00');
        }
        if(\Input::get("end_date")){
            $baseQuery->where('application_deadline',"<=",\Input::get("end_date").' 23:59:59');
        }
        $baseQuery->select(['id', 'name', 'application_deadline','announcement_date','period','student_max','fee','published_at']);
        $dto = Datatables_new::makeDto($baseQuery);
        //get all course id
        $courseIds = '';
        $appliers = '';
        $students = '';
        foreach($dto['data'] as $key=>$item){
            $courseIds[$key] = $item->id;
            $appliers[$item->id] = 0;
            $students[$item->id] = 0;
        }
        foreach($dto['data'] as $item){
            //appliers
            $course_id = $item->id;
            $course = Course::query()->findOrFail($course_id);
            $applications = $course->application()->get();
            foreach($applications as $application){
                //提交的申请表
                if ($application->pivot->submitted_at != null) {
                    $appliers[$course_id] ++;
                }
                // 通过
                if ($application->pivot->passed_at != null and $application->pivot->confirm == 1) {
                    $students[$course_id] ++;
                }
            }
        }
        $courses = Course::query()->get();
        foreach($courses as $course) {
            $applications = $course->application()->get();
            foreach($applications as $application){
                // 调班
                $transfer_course_id = $application->pivot->transfer_course_id;
                if ($transfer_course_id != null and in_array($transfer_course_id, $courseIds) ){
                    if ($application->pivot->confirm == true){//确认调班
                        $students[$transfer_course_id] ++;
                    }
                }
                // 延期
                $postpone_course_id = $application->pivot->postpone_course_id;
                if ($transfer_course_id != null and in_array($postpone_course_id, $courseIds) ){
                    if ($application->pivot->confirm == true){
                        $students[$postpone_course_id] ++;
                    }
                }
            }
        }
        foreach($dto['data'] as $item){
            $item->appliers = $appliers[$item->id];
            $item->students = $students[$item->id];
        }
        return $dto;
    }

    public function index_unpub()
    {
        return view('course.unpub');
    }

    public function indexDt_unpub(Request $request){
        $baseQuery = \DB::table('course')->whereNull('published_at')
            ->select(['id', 'name', 'application_deadline','announcement_date','period','student_max']);
        $dto = Datatables_new::makeDto($baseQuery);
        //get all course id
        $courseIds = '';
        $appliers = '';
        $students = '';
        foreach($dto['data'] as $key=>$item){
            $courseIds[$key] = $item->id;
            $appliers[$item->id] = 0;
            $students[$item->id] = 0;
        }

        foreach($dto['data'] as $item){
            $course_id = $item->id;
            $course = Course::query()->findOrFail($course_id);
            $applications = $course->application()->get();
            foreach($applications as $application){
                //提交的申请表
                if ($application->pivot->submitted_at != null) {
                    $appliers[$course_id] ++;
                }
                // 通过
                if ($application->pivot->passed_at != null and $application->pivot->confirm == 1) {
                    $students[$course_id] ++;
                }
            }
        }
        $courses = Course::query()->get();
        foreach($courses as $course) {
            $applications = $course->application()->get();
            foreach($applications as $application){
                // 调班
                $transfer_course_id = $application->pivot->transfer_course_id;
                if ($transfer_course_id != null and $courseIds !=null) {
                    if ($transfer_course_id != null and in_array($transfer_course_id, $courseIds)) {
                        if ($application->pivot->confirm == true) {//确认调班
                            $students[$transfer_course_id]++;
                        }
                    }
                }
                // 延期
                $postpone_course_id = $application->pivot->postpone_course_id;
                if ($postpone_course_id != null and $courseIds != null) {
                    if ($transfer_course_id != null and in_array($postpone_course_id, $courseIds)) {
                        if ($application->pivot->confirm == true) {
                            $students[$postpone_course_id]++;
                        }
                    }
                }
            }
        }
        foreach($dto['data'] as $item){
            $item->appliers = $appliers[$item->id];
            $item->students = $students[$item->id];
        }
        return $dto;
    }
    public function unpublish($id){
        $course = Course::query()->findOrFail($id);
        $now = Carbon::now();
        $course->published_at = null;
        $course->deleted_at = $now;
        $course->save();
        return redirect()->back()->with('success','已取消发布该课程');
    }
    public function publish($id){
        $course = Course::query()->findOrFail($id);
        $now = Carbon::now();
        $course->published_at = $now;
        $course->deleted_at = null;
        $course->save();
        return redirect()->back()->with('success','发布成功');
    }
    public function add(){
        return view('course.add');
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'application_deadline' => 'required|date',
            'announcement_date'=>'required|date',
            'enrollment_date' => 'required|date',
            'graduation_date' => 'required|date',
            'period' => 'required',
            'student_max' => 'required|numeric',
            'fee' => 'required|numeric'
           // 'detail_info' => 'required|text',
           // 'enrollment_info'=>'required|text',

        ], [], [
            'name' => '姓名',
            'application_deadline' => '申请截止日期',
            'announcement_date' => '发榜日期',
            'enrollment_date' => '报到日期',
            'graduation_date' => '结业日期',
            'period' => '进修时间',
            'student_max' => '招生人数',
            'fee' => '进修费用'
        ]);
        $attributes = array_only($request->all(), [
            'name',
            'application_deadline',
            'announcement_date',
            'enrollment_date',
            'graduation_date',
            'student_max',
            'period',
            'fee',
            'detail_info'
           // 'enrollment_info'
           // 'attachment'
        ]);

        //dd($attributes);
        Course::unguard();//???
        $course = Course::create($attributes);
        //add attachment
        $course_id = $course['attributes']['id'];
        $attachments = $request->file('attachment');
        if(count($attachments)>0) {
            $storage_path = storage_path();
            $file_path = $storage_path . '/attachment/' . $course_id;
            $i = 0;
            foreach ($attachments as $attachment) {
                //insert into database
                if ($attachment == null)
                    continue;
                $item['class_id'] = $course_id;
                $item['name'] = $attachment->getClientOriginalName();

                //save file to storage
                $extension = $attachment->getClientOriginalExtension();
                $file_name = $i . "." . $extension;
                $i++;
                $attachment->move($file_path, $file_name);
                $item['url'] = $file_path . '/' . $file_name;
                Attachment::unguard();
                Attachment::create($item);
            }
        }

        return redirect(route('course_unpub'))->with('success', '添加进修班成功');
    }
    public function edit($id){
        $course = Course::query()->findOrFail($id);
        $attachments = Attachment::query()->where("class_id","=",$id)->get();
        return view('course.edit',['course'=>$course,'attachments'=>$attachments]);
    }
    public function update($id, Request $request){
        $this->validate($request,[
            'name' => 'sometimes|required',
            'application_deadline' => 'sometimes|required|date',
            'announcement_date' => 'sometimes|required|date',
            'enrollment_date' => 'sometimes|required|date',
            'graduation_date' => 'sometimes|required|date',
            'period' => 'sometimes|required',
            'student_max' => 'sometimes|required|integer',
            'fee' => 'sometimes|required|numeric'
        ],
            [],
            [
            'name' => '进修班名称',
            'application_deadline' => '申请截止日期',
            'announcement_date' => '发榜日期',
            'enrollment_date' => '报到日期',
            'graduation_date' => '结业日期',
                'period' => '进修时间',
            'student_max' => '招收学员人数',
            'fee' => '进修费用'
        ]);
        $updateData = array_only($request->all(),[
            'name',
            'application_deadline',
            'announcement_date',
            'enrollment_date',
            'graduation_date',
            'student_max',
            'period',
            'fee',
            'detail_info'
           //'enrollment_info'
        ]);
        $course = Course::query()->findOrFail($id);
        //attachment appending
        $attachments = $request->file('attachment');
        //dd($attachments);
        if($attachments[0] != null and count($attachments) > 0) {//upload new attachment
            $storage_path = storage_path();
            $file_path = $storage_path . '/attachment/' . $id;
            $files = glob($file_path.'/*');
            //get max value of basename
            $max = 0 ;
            foreach ($files as $file) {
                if ($max < intval(basename($file))) {
                    $max = intval(basename($file));
                }
            }
            $max++;
            foreach ($attachments as $attachment) {
                $item['class_id'] = $id;
                $item['name'] = $attachment->getClientOriginalName();
                $extension = $attachment->getClientOriginalExtension();
                $file_name = $max . "." . $extension;
                $max++;
                $item['url'] = $file_path . '/' . $file_name;
                $attachment->move($file_path, $file_name);
                Attachment::unguard();
                Attachment::create($item);
            }
        }

        Course::unguard();
        $course->update($updateData);
        //return redirect()->back()->with('success','更新成功');
        return redirect(route('course'))->with('success','更新成功');
    }
    public function delete_attachment($id, $name){
        $tmp = Attachment::where('class_id','=',$id)->where('name','=',$name)->get(['url']);
        Attachment::where('class_id','=',$id)->where('name','=',$name)->delete();
        $url = $tmp[0]['attributes']['url'];
        unlink($url);
        return redirect()->back()->with('success','删除附件成功');
    }

    public function appliers($id){
        $courses = Course::whereNotNull("published_at")->get(['id','name']);
        return view('application_admin.index',['courses'=>$courses,'id'=>$id]);
    }

    public function students($id){
        $courses = Course::whereNotNull("published_at")->get(['id','name']);
        return view('student.index',['courses'=>$courses,'id'=>$id]);
    }
}