<?php
/**
 * Created by PhpStorm.
 * User: zgc
 * Date: 2015/7/22
 * Time: 10:07
 */
namespace App\Http\Controllers;
//require_once 'src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

use App\Datatables\Datatables;
use App\Http\Requests;

use App\Models\Admin_duty;
use App\Models\Application;
use App\Models\Attachment;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Director;
use App\Models\Education;
use App\Models\Org_rank;
use App\Models\Paper;
use App\Models\Region;
use App\Models\Relation;
use App\Models\Resume;
use App\Models\Tech_duty;
use App\Support\Datatables_new;
use App\Support\ExportHelper;
use Carbon\Carbon;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpWord\TemplateProcessor;
use Storage;

class ClientController extends Controller
{
    public function info(Request $request)
{
    $index = $request['index'];
        //login
    $application_id = session()->get('application_id');
    $application = Application::all()->find($application_id);
    if ($application->name == null) {
        $now = Carbon::now();
        $courses = Course::query()->whereNotNull("published_at")->where("application_deadline", ">", $now)->get([
            'id',
            'name'
        ]);
        $tech_duty = Tech_duty::query()->get(['id', 'name']);
        $admin_duty = Admin_duty::query()->get(['id', 'name']);
        $degrees = Degree::query()->get(['id', 'name']);
        $org_ranks = Org_rank::query()->get(['id', 'name']);
        $regions = Region::query()->get(['id', 'name']);
        $phone_number = session()->get('phone_number');
        return view('application.info', [
            'courses' => $courses,
            'tech_duty' => $tech_duty,
            'admin_duty' => $admin_duty,
            'degrees' => $degrees,
            'regions' => $regions,
            'org_ranks' => $org_ranks,
            'phone_number' => $phone_number
        ]);
    } else {
            $id = $application_id;
            //已选课程区间
            $application_course_items = \DB::table('application_course_result')->where('application_id',$id)->select()->get();
            $periods = '';
            foreach($application_course_items as $index=>$item) {
                if ($item->postpone_course_id != null and $item->confirm == 1) {//延期
                    $course_id = $item->postpone_course_id;
                } elseif ($item->transfer_course_id != null and $item->confirm == 1) {//调班
                    $course_id = $item->transfer_course_id;
                } else
                    $course_id = $item->course_id;
                // 课程开课区间
                $course = Course::query()->where('id',$course_id)->first();
                $periods[$index] = ['0'=>$course->enrollment_date,'1'=>$course->graduation_date];//所选课程开课区间
            }
            //筛选可以选的课程
            $courses = Course::query()->whereNotNull("published_at")->where("application_deadline", ">", Carbon::now())->get();
            foreach($courses as $key=>$course) {
                $start = $course->enrollment_date;
                $end = $course->graduation_date;
                if ($periods == null) {
                    break;
                }
                foreach($periods as $period) {
                    if (($period[0] < $start and $period[1] <= $start) or ($period[0] >= $end and $period[1] > $end)) {
                        continue;
                    } else {
                        unset($courses[$key]);
                    }
                }
            }
        $relation = Relation::query()->where('application_id',$id)->first();
        $director = Director::query()->where('application_id',$id)->first();
        $tech_duty = Tech_duty::query()->get(['id','name']);
        $admin_duty = Admin_duty::query()->get(['id','name']);
        $degrees = Degree::query()->get(['id','name']);
        $org_ranks = Org_rank::query()->get(['id','name']);
        $regions = Region::query()->get(['id','name']);
        $application = Application::query()->findOrFail($id);
        $resumes = Resume::query()->where('application_id','=',$id)->get(['start_date','end_date','organization','title']);
        $educations = Education::query()->where('application_id','=',$id)->get(['start_date','graduation_date','school','major','degree']);
        $papers = Paper::query()->where('application_id','=',$id)->get(['name','journal','publish_date']);
        //处理所选课程冲突
        $indice[0] = '';
        foreach($courses as $key=>$course) {
            $indice[$key] = $course->id;
        }
        $index = $request['index'];
        if ($index != null) {
            if (in_array($index, $indice) == 0) {
                //return redirect(route('class'))->with('warning', '无法选该课程，因为您已选课程与该课程进修时间冲突');
                return view('application.classList',['alert'=>'1']);
            }
        }
        //处理所有可选课程为空情形
        if (count($courses) == 0) {
            //return redirect(route('class'))->with('warning','无可选课程，因为您已选课程与所有课程进修时间冲突');
            return view('application.classList',['alert'=>'2']);
        }

        return view('application.edit', [
                'application' => $application,
                'courses' => $courses,
                'index' => $index,
                'tech_duty' => $tech_duty,
                'admin_duty' => $admin_duty,
                'degrees' => $degrees,
                'regions' => $regions,
                'org_ranks' => $org_ranks,
                'resumes' => $resumes,
                'educations' => $educations,
                'papers' => $papers,
                'relation' => $relation,
                'director' => $director
            ]);

    }
}

    public function store(Request $request){
        $attributes = array_only($request->all(), [
            'name',
            'gender',
            'nation',
            'phone_number',
            'birthday',
            'birthplace',
            'email',
            'id_no',
            'organization',
            'address',
            'zip_code',
            'certificate_id',
            'speciality',
            'region_id',
            'tech_duty_id',
            'admin_duty_id',
            'degree_id',
            'org_rank_id',
            'accommodation'
        ]);
        //save photo
        //save application
        $application_id = session()->get('application_id');
        Application::unguard();
        Application::where('id',$application_id)->update($attributes);
        $apply = Application::where('id',$application_id)->get();
        //get id of application
        $id = $apply[0]['id'];
        if($request->hasFile('photo')) {
            $storage_path = storage_path();
            $file_path = $storage_path . "/photos/" . "2015";
            $file_name = $id . ".jpg";
            $photo = $request->file('photo');
            $photo->move($file_path, $file_name);
        }
        //relation_table
        $apply[0]->course()->attach($request['class_id']);
        //relation
        $relation['name'] = $request['relation'];
        $relation['relationship'] = $request['relationship'];
        $relation['phone_number'] = $request['relation_phone'];
        $relation['application_id'] = $id;
        Relation::unguard();
        Relation::create($relation);
        //director
        $director['name'] = $request['director'];
        $director['duty'] = $request['director_duty'];
        $director['phone_number'] = $request['director_phone'];
        $director['application_id'] = $id;
        Director::unguard();
        Director::create($director);
        //education
        $educations = array_only($request->all(),[
            'education'
        ]);

        foreach($educations['education'] as $item){
            $education['application_id'] = $id;
            $education['start_date'] = $item['start_date'];
            $education['graduation_date'] = $item['graduate_date'];
            $education['school'] = $item['school'];
            $education['major'] = $item['major'];
            $education['degree'] = $item['degree'];
            //dd($education);
            Education::unguard();
            Education::create($education);
        }
        //resume
        $resumes = array_only($request->all(),[
            'resume'
        ]);
        //dd($resumes);


        foreach ($resumes['resume'] as $item) {
            $resume['application_id'] = $id;
            $resume['start_date'] = $item['start_date'];
            $resume['end_date'] = $item['end_date'];
            $resume['organization'] = $item['organization'];
            $resume['title'] = $item['title'];
            //dd($resume);
            Resume::unguard();
            Resume::create($resume);
        }
        //paper
        $papers = array_only($request->all(),[
            'paper'
        ]);

                foreach($papers['paper'] as $item) {
                    if($item['name'] == ''){
                        continue;
                    }
                    else {
                        $paper['application_id'] = $id;
                        $paper['name'] = $item['name'];
                        $paper['journal'] = $item['journal'];
                        $paper['publish_date'] = $item['publish_date'];
                        Paper::unguard();
                        Paper::create($paper);
                    }
                }

        return redirect(route('apply'))->with('success', '保存成功');
    }

    public function apply(){
        //$application = Application::query()->findOrFail($id);
        $application_id = session()->get("application_id");
        //get all applications
        $applier = Application::query()->findOrFail($application_id);
        $applications = $applier->course()->get();
        if(count($applications) == 0) {
            return redirect('/class');
        }
        $data = '';
        foreach($applications as $app) {
            $course_id = $app->id;
            $relation = \DB::table('application_course_result')->where('application_id',$application_id)
                ->where('course_id',$course_id)->where('submitted_at',$app->pivot->submitted_at)->get();
            $id = $relation[0]->id;
            //search if file has submitted
            $storage_path = storage_path();
            $extensions = array(".jpg",".png",".bmp",".pdf",".jpeg",".PNG",".JPG",".BMP",".PDF",".doc",".docx");
            $flag = false;
            foreach($extensions as $extension){
                $file_path = $storage_path . "/applications" . "/2015/" . $id . $extension;
                if(file_exists($file_path) == true){
                    $flag = true;
                    break;
                }
            }
            if($flag==true) {
                $data[$id]['edit_state'] = 1;
                $data[$id]['submit_state'] = 1;
            }
            else{
                $data[$id]['edit_state'] = 0;
                $data[$id]['submit_state'] = 0;
            }
            //check result,after announcement_date
            $course = Course::query()->findOrFail($app->pivot->course_id);
            if(Carbon::now() > $course["announcement_date"]) {//after announce
                if ($app->pivot->passed_at == null and $app->pivot->rejected_at == null and $app->pivot->transfer_course_id == null and $app->pivot->postpone_course_id==null) {
                    $data[$id]['check_state'] = 1;//show reject status
                }
                if($app->pivot->passed_at == null and $app->pivot->rejected_at != null and $app->pivot->transfer_course_id == null and $app->pivot->postpone_course_id==null){//reject
                    $data[$id]['check_state'] = 1;
                }
                if($app->pivot->passed_at != null and $app->pivot->rejected_at == null and $app->pivot->transfer_course_id == null and $app->pivot->postpone_course_id==null){//pass
                    if ($app->pivot->confirm == null)//待确认通过
                        $data[$id]['check_state'] = 2;
                    if ($app->pivot->confirm == "0")//拒绝通过
                        $data[$id]['check_state'] = 20;
                    if ($app->pivot->confirm == "1")//已确认通过
                        $data[$id]['check_state'] = 21;
                }
                if ($app->pivot->passed_at == null and $app->pivot->rejected_at == null and $app->pivot->transfer_course_id != null and $app->pivot->postpone_course_id==null) {
                    $course['transfer_course_name'] = Course::query()->findOrFail($app->pivot->transfer_course_id)->name;
                    $course['transfer_course_name_id'] = Course::query()->findOrFail($app->pivot->transfer_course_id)->id;
                    if ($app->pivot->confirm == null)//待确认调班
                        $data[$id]['check_state'] = 3;
                    if ($app->pivot->confirm == "0")//拒绝调班
                        $data[$id]['check_state'] = 30;
                    if ($app->pivot->confirm == "1")//已确认调班
                        $data[$id]['check_state'] = 31;
                }
                if ($app->pivot->passed_at == null and $app->pivot->rejected_at == null and $app->pivot->transfer_course_id == null and $app->pivot->postpone_course_id!=null) {
                    $course['postpone_course_name'] = Course::query()->findOrFail($app->pivot->postpone_course_id)->name;
                    $course['postpone_course_name_id'] = Course::query()->findOrFail($app->pivot->postpone_course_id)->id;
                    if ($app->pivot->confirm == null)//待确认延期
                        $data[$id]['check_state'] = 4;
                    if ($app->pivot->confirm == "0")//拒绝延期
                        $data[$id]['check_state'] = 40;
                    if ($app->pivot->confirm == "1")//已确认延期
                        $data[$id]['check_state'] = 41;
                }
            }
            else {
                $data[$id]['check_state'] = 0;
            }
            if($data[$id]['check_state'] == 30 ||$data[$id]['check_state'] == 31) {
                $data[$id]['course'] = Course::query()->findOrFail($app->pivot->transfer_course_id);
            }else {
                $data[$id]['course'] = $course;
            }
        }
        return view('application.apply',['id'=>$application_id,'data'=>$data]);
    }

    public function GetPhoto($year,$id){
        $storage_path = storage_path();
        $file_path = $storage_path . "/photos/"  . $year . "/" . $id . ".jpg";
        return response()->download($file_path);
    }

    public function editRefresh(){
        //dd("\\photos\\"  . "2015" . "\\" . "2" . ".jpg");
        $id = session()->get('application_id');
        $courses = Course::query()->get(['id','name']);
        $tech_duty = Tech_duty::query()->get(['id','name']);
        $admin_duty = Admin_duty::query()->get(['id','name']);
        $degrees = Degree::query()->get(['id','name']);
        $org_ranks = Org_rank::query()->get(['id','name']);
        $regions = Region::query()->get(['id','name']);
        $application = Application::query()->findOrFail($id);
        $resumes = Resume::query()->where('application_id','=',$id)->get(['start_date','end_date','organization','title']);
        $educations = Education::query()->where('application_id','=',$id)->get(['start_date','graduation_date','school','major','degree']);
        $papers = Paper::query()->where('application_id','=',$id)->get(['name','journal','publish_date']);
        //$edu = \DB::table('education')->where('application_id','=',$id)->select(['start_date','graduation_date','school','major','degree']);

//        dd(json_encode($educations));

            return view('application.edit', [
                'application' => $application,
                'courses' => $courses,
                'tech_duty' => $tech_duty,
                'admin_duty' => $admin_duty,
                'degrees' => $degrees,
                'regions' => $regions,
                'org_ranks' => $org_ranks,
                'resumes' => $resumes,
                'educations' => $educations,
                'papers' => $papers
            ]);
    }
    public function edit(Request $request, $course_id){
        //dd("\\photos\\"  . "2015" . "\\" . "2" . ".jpg");
        $id = $request->all()['id'];
        $courses = Course::query()->where('id',$course_id)->get(['id','name']);
        $index = $courses[0]->id;
        $relation = Relation::query()->where('application_id',$id)->first();
        $director = Director::query()->where('application_id',$id)->first();
        $tech_duty = Tech_duty::query()->get(['id','name']);
        $admin_duty = Admin_duty::query()->get(['id','name']);
        $degrees = Degree::query()->get(['id','name']);
        $org_ranks = Org_rank::query()->get(['id','name']);
        $regions = Region::query()->get(['id','name']);
        $application = Application::query()->findOrFail($id);
        $resumes = Resume::query()->where('application_id','=',$id)->get(['start_date','end_date','organization','title']);
        $educations = Education::query()->where('application_id','=',$id)->get(['start_date','graduation_date','school','major','degree']);
        $papers = Paper::query()->where('application_id','=',$id)->get(['name','journal','publish_date']);
        if($request->all()['preview'] == "1"){
            return redirect('preview/'.$course_id);
        }
        else {
            return view('application.edit', [
                'application' => $application,
                'courses' => $courses,
                'index' => $index,
                'tech_duty' => $tech_duty,
                'admin_duty' => $admin_duty,
                'degrees' => $degrees,
                'regions' => $regions,
                'org_ranks' => $org_ranks,
                'resumes' => $resumes,
                'educations' => $educations,
                'papers' => $papers,
                'relation' => $relation,
                'director' => $director
            ]);
        }
        }

    public function update( Request $request){
        $this->validate($request,[
            "phone_number"=>"sometimes|required|size:11",
            "photo"=>"sometimes|required|image",
            "zip_code"=>"sometimes|required|size:6",
            //"certificate_id"=>"sometimes|required|size:24"
        ],[],[
            "phone_number"=>"手机号码",
            "photo"=>"证件照",
            "zip_code"=>"邮政编码",
           // "certificate_id"=>"医师资格证书编码"
        ]);
        $id = $request->all()['id'];
        $attributes = array_only($request->all(), [
            'name',
            'gender',
            'nation',
            'phone_number',
            'birthday',
            'birthplace',
            'email',
            'id_no',
            'organization',
            'address',
            'zip_code',
            'certificate_id',
            'speciality',
            'region_id',
            'tech_duty_id',
            'admin_duty_id',
            'degree_id',
            'org_rank_id',
            'accommodation'
        ]);
        //save photo
        if($request->hasFile('photo')) {
            $storage_path = storage_path();
            $file_path = $storage_path . "/photos/" . "2015";
            $file_name = $id . ".jpg";
            $photo = $request->file('photo');
            $photo->move($file_path, $file_name);
        }

        //save application
        $apply = Application::query()->findOrFail($id);
        Application::unguard();
        $apply->update($attributes);
        //get id of application
        //relation table
        $tmp = \DB::table('application_course_result')->where('application_id',$id)->where('course_id',$request['class_id'])->select()->get();
        if (count($tmp) == 0)
            $apply->course()->attach($request['class_id']);
        //relation
        \DB::table('relation')->where('application_id',$id)->delete();
        $relation['name'] = $request['relation'];
        $relation['relationship'] = $request['relationship'];
        $relation['phone_number'] = $request['relation_phone'];
        $relation['application_id'] = $id;
        Relation::unguard();
        Relation::create($relation);
        //director
        \DB::table('director')->where('application_id',$id)->delete();
        $director['name'] = $request['director'];
        $director['duty'] = $request['director_duty'];
        $director['phone_number'] = $request['director_phone'];
        $director['application_id'] = $id;
        Director::unguard();
        Director::create($director);
        //education
        //delete original record
        Education::where("application_id",'=',$id)->delete();
        //create new record
        $educations = array_only($request->all(),[
            'education'
        ]);
        //dd($educations);

        foreach($educations['education'] as $item){
            //dd($item);
            $education['application_id'] = $id;
            $education['start_date'] = $item['start_date'];
            $education['graduation_date'] = $item['graduate_date'];
            $education['school'] = $item['school'];
            $education['major'] = $item['major'];
            $education['degree'] = $item['degree'];
            //dd($education);
            Education::unguard();
            Education::create($education);
        }
        //resume
        //delete
        Resume::where("application_id","=",$id)->delete();
        //
        $resumes = array_only($request->all(),[
            'resume'
        ]);
        //dd($resumes);

        foreach ($resumes['resume'] as $item) {
            $resume['application_id'] = $id;
            $resume['start_date'] = $item['start_date'];
            $resume['end_date'] = $item['end_date'];
            $resume['organization'] = $item['organization'];
            $resume['title'] = $item['title'];
            //dd($resume);
            Resume::unguard();
            Resume::create($resume);
        }
        //paper
        //delete
        Paper::where("application_id","=",$id)->delete();
        //
        $papers = array_only($request->all(),[
            'paper'
        ]);

            foreach ($papers['paper'] as $item) {
                if ($item['name'] == '') {
                    continue;
                } else {
                $paper['application_id'] = $id;
                $paper['name'] = $item['name'];
                $paper['journal'] = $item['journal'];
                $paper['publish_date'] = $item['publish_date'];
                Paper::unguard();
                Paper::create($paper);
            }
        }
        //$course_id = $apply["class_id"];
        //$course = Course::query()->findOrFail($course_id);
        return redirect(route("apply"))->with('success', '操作成功');
    }

    public function query(Request $request){
        $attr = $request->all();
        $application = Application::query()->where("name","=",$attr["name"])->where("id_no","=",$attr["id_no"])->get();
        if(count($application) == 0){
            session()->put('login',1);//login
            //return redirect(route($from_url));
            return view('application.query',["status"=>0]);//display warning
        }
        else {
            $id = $application[0]["attributes"]["id"];
            session()->put('application_id',$id);//session
            session()->put('login',1);//login
            $from_url = $request['from'];
            if ($from_url == null) {
                return redirect(route('apply'));
            } else {
                return redirect(route($from_url));
            }
        }

    }

    public function validating(Request $request){
        $attr = $request->all();
        $application = Application::query()->where("name","=",$attr["name"])->where("id_no","=",$attr["id_no"])->get();
        if(count($application) == 0){//new user
            $from_url = $request['from'];
            $index = $request['index'];
            session()->put('login',1);//login
            session()->put('id_no',$attr['id_no']);
            if ($index == null)
                return redirect(route($from_url));
            else
                return redirect('info?index='.$index);
            //return view('application.query',["status"=>0]);//display warning
        }
        else {
            $id = $application[0]["attributes"]["id"];
            session()->put('application_id',$id);//session
            session()->put('login',1);//login
            session()->put('id_no',$attr['id_no']);
            $from_url = $request['from'];
            $index = $request['index'];
            if ($from_url == null) {
                return redirect(route('apply'));
            } else {
                if ($index == null)
                    return redirect(route($from_url));
                else
                    return redirect('info?index='.$index);
            }
        }

    }

    public function classIndex(){
        return view("application.classList",['alert'=>'']);
    }
    public function classDt(){
        $baseQuery = \DB::table('course')->whereNotNull('published_at');
        $now = Carbon::now();
        $baseQuery->where('application_deadline',">",$now);
        $baseQuery->select(['id', 'name', 'application_deadline','period','student_max','fee','enrollment_date','graduation_date']);
        $dto = Datatables_new::makeDto($baseQuery);
        //foreach ($dto['data'] as $item) {
            //$item['application_deadline'] = date('Y-m-d',$item['application_deadline']->getTimestamp);
           // dd($item["id"]);
        $id = session()->get('application_id');
        if ($id != null) {
            //已选课程区间
            $application_course_items = \DB::table('application_course_result')->where('application_id',
                $id)->select()->get();
            $periods = '';
            foreach ($application_course_items as $index => $item) {
                if ($item->postpone_course_id != null and $item->confirm == 1) {//延期
                    $course_id = $item->postpone_course_id;
                } elseif ($item->transfer_course_id != null and $item->confirm == 1) {//调班
                    $course_id = $item->transfer_course_id;
                } else {
                    $course_id = $item->course_id;
                }
                // 课程开课区间
                $course = Course::query()->where('id', $course_id)->first();
                $periods[$index] = ['0' => $course->enrollment_date, '1' => $course->graduation_date];//所选课程开课区间
            }
            if ($periods == null) {
                return $dto;
            }
            //筛选可以选的课程
            foreach ($dto['data'] as $key => $course) {
                $start = $course->enrollment_date;
                $end = $course->graduation_date;
                foreach ($periods as $period) {
                    if (($period[0] < $start and $period[1] <= $start) or ($period[0] >= $end and $period[1] > $end)) {

                    } else {
                        unset($dto['data'][$key]);
                        $dto['recordsTotal'] -- ;
                        $dto['recordsFiltered'] -- ;
                    }
                }
            }
        }
        $dto['data'] = array_values($dto['data']);
        return $dto;
    }

    public function info_course($id){
        $login = session()->get('login');
        // not login
        if ($login == null) {
            return redirect('/query?from=class');
        }
        // login
        $now = Carbon::now();
        $courses = Course::query()->whereNotNull("published_at")->where('application_deadline',">",$now)->get(['id','name']);//before deadline
        $tech_duty = Tech_duty::query()->get(['id','name']);
        $admin_duty = Admin_duty::query()->get(['id','name']);
        $degrees = Degree::query()->get(['id','name']);
        $org_ranks = Org_rank::query()->get(['id','name']);
        $regions = Region::query()->get(['id','name']);

        return view('application.info_course',['id'=>$id,'courses'=>$courses,'tech_duty'=>$tech_duty,'admin_duty'=>$admin_duty,'degrees'=>$degrees,'regions'=>$regions,'org_ranks'=>$org_ranks]);
    }

    public function course_enroll($id){
        $course = Course::query()->findOrFail($id);
        return view('application.enroll_info',['course'=>$course]);
    }

    //upload application of printing
    public function upload(Request $request, $id){
        if($request->hasFile('file_path')){
            $storage_path = storage_path();
            $file_path = $storage_path . "/applications" . "/2015";
            //$extension=pathinfo($request->file('file_path'), PATHINFO_EXTENSION);//extension
            $file_name = $id .'.'. $request->file('file_path')->getClientOriginalExtension();
            $photo = $request->file('file_path');
            $photo->move($file_path, $file_name);
            \DB::table('application_course_result')->where('id',$id)->update(['submitted_at'=>Carbon::now()]);
        }
        //change state
        /*$storage_path = storage_path();
        $file_path = $storage_path . "/applications" . "/2015/" . $id . ".jpg";
        if(file_exists($file_path)==true) {
            session()->put("edit_state", 1);
            session()->put("submit_state", 1);
        }
        else{
            session()->put("edit_state", 0);
            session()->put("submit_state", 0);
        }*/
        return redirect('/apply?index='.$id)->with("success",'上传申请表成功');
    }

    public function description($id){
        $course = Course::query()->findOrFail($id);
        $attachments = Attachment::query()->where('class_id','=',$id)->get();
        //dd($attachments);
        return view('application.description',['course'=>$course,'attachments'=>$attachments]);
    }
    public function preview($course_id){
        $id = session()->get('application_id');
        $application = Application::query()->findOrFail($id);
        $course = Course::query()->findOrFail($course_id);
        $flag = 0;
        //gender
        if($application['gender'] == 1){
            $gender = "男";
        }else{
            $gender = "女";
        }
        if($application['accommodation'] == 1){
            $accommodation = "需要";
        }else{
            $accommodation = "不需要";
        }
        //tech_duty
        $tech_duty = Tech_duty::query()->findOrFail($application['tech_duty_id']);
        $admin_duty = Admin_duty::query()->findOrFail($application['admin_duty_id']);
        $degree = Degree::query()->findOrFail($application['degree_id']);
        $org_rank = Org_rank::query()->findOrFail($application['org_rank_id']);
        $region = Region::query()->findOrFail($application['region_id']);
        //relation
        $relation = Relation::query()->where('application_id',$id)->first();
        //director
        $director = Director::query()->where('application_id',$id)->first();
        //multi
        $resumes = Resume::query()->where('application_id','=',$id)->get(['start_date','end_date','organization','title']);
        $educations = Education::query()->where('application_id','=',$id)->get(['start_date','graduation_date','school','major','degree']);
        $papers = Paper::query()->where('application_id','=',$id)->get(['name','journal','publish_date']);
        return view("application.preview",['application'=>$application,'course'=>$course,"gender"=>$gender,"tech_duty"=>$tech_duty,"admin_duty"=>$admin_duty,"degree"=>$degree,"org_rank"=>$org_rank,"region"=>$region,
                    "resumes"=>$resumes,"educations"=>$educations,"papers"=>$papers,"flag"=>$flag,'relation'=>$relation,'director'=>$director,'accommodation'=>$accommodation]);
    }

    /**
     * @param $id
     */
    public function download_enrollment($id){
        $app = \DB::table('application_course_result')->where('application_course_result.id',$id)->get();
        if($app[0]->confirm == 1 && $app[0]-> transfer_course_id != null) {
            $application = \DB::table('application_course_result')->where('application_course_result.id',$id)
                ->leftJoin('course','application_course_result.transfer_course_id','=','course.id')
                ->leftJoin('application','application_course_result.application_id','=','application.id')
                ->select(['application_course_result.id as id','application.name as name',
                    'organization','accommodation','period','enrollment_date','fee','announcement_date',
                    'course.name as course_name','application_id'])->first();
        } else {
            $application = \DB::table('application_course_result')->where('application_course_result.id',$id)
                ->leftJoin('course','application_course_result.course_id','=','course.id')
                ->leftJoin('application','application_course_result.application_id','=','application.id')
                ->select(['application_course_result.id as id','application.name as name',
                    'organization','accommodation','period','enrollment_date','fee','announcement_date',
                    'course.name as course_name','application_id'])->first();
        }
        $application = (array)$application;
        $templateProcessor = new TemplateProcessor(base_path('/template.docx'));
        $templateProcessor->setValue('organization', $application['organization']);
        $templateProcessor->setValue('name', $application['name']);
        $templateProcessor->setValue('course', $application['course_name']);
        $templateProcessor->setValue('period', $application['period']);
        $templateProcessor->setValue('enrollment', $application['enrollment_date']);
        $templateProcessor->setValue('fee', $application['fee']);
        $templateProcessor->setValue('announcement', $application['announcement_date']);
        $file_name = 'docx/' . $id . '.docx';
        $templateProcessor->saveAs(storage_path($file_name));
        return response()->download(storage_path($file_name),'进修通知及报道须知.docx');
    }
    public function download_application($id){
        $application = \DB::table('application_course_result')->where('application_course_result.id',$id)
            ->leftJoin('course','application_course_result.course_id','=','course.id')
            ->leftJoin('application','application_course_result.application_id','=','application.id')
            ->select(['application_course_result.id as id','application.name as name','gender','nation','birthday','phone_number','birthplace','email','id_no',
            'tech_duty_id','admin_duty_id','degree_id','org_rank_id','region_id','organization','accommodation',
            'certificate_id','speciality','zip_code','address','course.name as course_name','application_id'])->first();
        //$application = $application->columns;
        $templateProcessor = new TemplateProcessor(base_path('/template_application.docx'));
        $templateProcessor->setValue('name', $application->name);
        if($application->gender == 1)
            $templateProcessor->setValue('gender',"男");
        else
            $templateProcessor->setValue('gender', "女");
        if($application->accommodation == '1')
            $templateProcessor->setValue('accommodation',"需要");
        else
            $templateProcessor->setValue('accommodation', "不需要");
        $templateProcessor->setValue('nation', $application->nation);
        $templateProcessor->setValue('birthday', $application->birthday);
        $templateProcessor->setValue('telephone', $application->phone_number);

        $templateProcessor->setValue('birthplace', $application->birthplace);
        $templateProcessor->setValue('email', $application->email);
        $templateProcessor->setValue('id_no', $application->id_no);
        $tech_duty = Tech_duty::query()->where("id",'=',$application->tech_duty_id)->get();
        $templateProcessor->setValue('tech_duty', $tech_duty['0']['attributes']['name']);
        $admin_duty = Admin_duty::query()->where("id",'=',$application->admin_duty_id)->get();
        $templateProcessor->setValue('admin_duty', $admin_duty['0']['attributes']['name']);
        $degree = Degree::query()->where("id",'=',$application->degree_id)->get();
        $templateProcessor->setValue('degree', $degree['0']['attributes']['name']);
        $org_rank = Org_rank::query()->where("id",'=',$application->org_rank_id)->get();
        $templateProcessor->setValue('org_rank', $org_rank['0']['attributes']['name']);
        $region = Region::query()->where("id",'=',$application->region_id)->get();
        $templateProcessor->setValue('region', $region['0']['attributes']['name']);
        $templateProcessor->setValue('organization', $application->organization);
        $templateProcessor->setValue('certification_id', $application->certificate_id);
        $templateProcessor->setValue('major', $application->speciality);
        $templateProcessor->setValue('zip_code', $application->zip_code);
        $templateProcessor->setValue('address', $application->address);
        //$course = Course::query()->where("id",'=',$application['class_id'])->get();
        $templateProcessor->setValue('course', $application->course_name);
        //relation
        $relation = Relation::query()->where('application_id',$application->application_id)->first();
        $relation = $relation['attributes'];
        $templateProcessor->setValue('relation', $relation['name']);
        $templateProcessor->setValue('relationship', $relation['relationship']);
        $templateProcessor->setValue('relation_phone', $relation['phone_number']);
        //director
        $director = Director::query()->where('application_id',$application->application_id)->first();
        $director = $director['attributes'];
        $templateProcessor->setValue('director', $director['name']);
        $templateProcessor->setValue('director_duty', $director['duty']);
        $templateProcessor->setValue('director_phone', $director['phone_number']);
        //resume
        $resumes = Resume::query()->where("application_id",'=',$application->application_id)->get();
        //dd($resumes);
        $str = '';
        $i = 0;
        foreach($resumes as $resume){
            $item = $resume['attributes'];
            $str_tmp = $item['start_date'] . ' ~ ' . $item['end_date'] . '，' . $item['organization'].'，'.$item['title'];
            if($i == 0){
                $str = $str_tmp;
                $i = 1;
            }
            else{
                $str = $str . "<w:br/>" . $str_tmp;
            }
        }
        $templateProcessor->setValue('Resume', $str);
        //education
        $educations = Education::query()->where("application_id",'=',$application->application_id)->get();
        $str = '';
        $i = 0;
        foreach($educations as $education){
            $item = $education['attributes'];
            $str_tmp = $item['start_date'] . ' ~ ' . $item['graduation_date'] . '，' . $item['school'].'，'.$item['major'].'，'.$item['degree'];
            if($i == 0){
                $str = $str_tmp;
                $i = 1;
            }
            else{
                $str = $str . "<w:br/>" . $str_tmp;
            }
        }
        $templateProcessor->setValue('Education', $str);
        //paper
        $papers = Paper::query()->where("application_id",'=',$application->application_id)->get();
        $str = '';
        $i = 0;
        foreach($papers as $paper){
            $item = $paper['attributes'];
            $str_tmp = $item['name'] . '，' . $item['publish_date'].'，'.$item['journal'];
            if($i == 0){
                $str = $str_tmp;
                $i = 1;
            }
            else{
                $str = $str . "<w:br/>" . $str_tmp;
            }
        }
        $templateProcessor->setValue('Paper', $str);
        $file_name = 'docx_application/' . $id . '.docx';
        $templateProcessor->saveAs(storage_path($file_name));
        return response()->download(storage_path($file_name),'进修申请表.docx');
    }
    public function agree($id){
       \DB::table('application_course_result')->where('id',$id)->update(['confirm'=>'1',"confirmed_at"=>Carbon::now()]);
        return redirect('/apply?index='.$id);
    }
    public function refuse($id){
        \DB::table('application_course_result')->where('id',$id)->update(['confirm'=>'0',"confirmed_at"=>Carbon::now()]);
        return redirect('/apply?index='.$id);
    }
    public function validator()
    {
        return view('application.validate');
    }
}