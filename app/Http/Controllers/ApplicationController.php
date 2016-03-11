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

use App\Models\Application;
use App\Models\Admin_duty;
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
use Carbon\Carbon;
use Hamcrest\Core\IsNot;
use Illuminate\Database\Eloquent\Model;use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\VarDumper\Cloner\Data;

class ApplicationController extends Controller
{
    public function index()
    {
        $courses = Course::whereNotNull("published_at")->get(['id','name']);
        $id = '';
        return view('application_admin.index',['courses'=>$courses,'id'=>$id]);
    }

    public function addRecommender($id){
        $application = \App\Models\Application::query()->findOrFail($id);
        return view("application_admin.addRecommender",["application"=>$application]);
    }

    public function RecommenderUpdate($id,Request $request){
        $application = \App\Models\Application::query()->findOrFail($id);
        $attr = $request->all();
        $application['recommender'] = $attr['recommender'];
        $application->save();
        return redirect(route('application'))->with("success","添加推荐人成功");
    }
    /**
     * @return array
     */
    public function indexDt(){
        $applications = \App\Models\Application::query()->get();
        //refresh application that has been submitted but not handled
        foreach($applications as $app){
            $application_undo = $app->course()->wherePivot('submitted_at','>',0)->wherePivot('passed_at',null)->wherePivot('rejected_at',null)->wherePivot('transfer_course_id',null)->wherePivot('postpone_course_id',null)->get();
            $now = Carbon::now();
            foreach($application_undo as $a){
                $class_id = $a->pivot->course_id;
                $course = Course::query()->findOrFail($class_id);
                $announce_date = $course->announcement_date;
                if($now > $announce_date){
                    $a->pivot->rejected_at = $now;
                    $a->pivot->save();
                }
            }
        }

        $baseQuery = \DB::table('application')
            ->rightJoin('application_course_result','application_course_result.application_id','=','application.id')
            ->leftJoin('degree', 'application.degree_id','=','degree.id')
            ->leftJoin('tech_duty', 'application.tech_duty_id','=','tech_duty.id')
            ->leftJoin('course', 'course.id', '=', 'course_id')
            ->whereNotNull('submitted_at');
        //time filter
        if (\Input::get("submitted_at")){
            $day = Carbon::createFromFormat('Y-m-d',\Input::get("submitted_at"));
            $baseQuery->where("submitted_at",">=",$day->copy()->startOfDay());
            $baseQuery->where("submitted_at","<=",$day->copy()->endOfDay());
        }
        //course filter
        if(\Input::get("course_id")){
            $baseQuery->where("course_id","=",\Input::get("course_id"));
        }
        //recommend filter
        if(\Input::get("recommend")){
            if(\Input::get("recommend")=='yes')//有推荐人
                $baseQuery->whereNotNull("recommender");
            else
                $baseQuery->whereNull("recommender");
        }
        //status filter
        if(\Input::get("status")) {
            if (\Input::get("status") == "no") {//未审核
                $baseQuery->whereNull('passed_at')->whereNull('rejected_at')->whereNull('transfer_course_id')->whereNull('postpone_course_id');

            } elseif (\Input::get("status") == "to_pass") {//待确认通过
                $baseQuery->whereNotNull('passed_at')->whereNull('rejected_at')->whereNull('transfer_course_id')->whereNull('postpone_course_id')->whereNull('confirm');
            } elseif (\Input::get("status") == "passed") {//已确认通过
                $baseQuery->whereNotNull('passed_at')->whereNull('rejected_at')->whereNull('transfer_course_id')->whereNull('postpone_course_id')->where('confirm',
                    1);
            } elseif (\Input::get("status") == "no_pass") {//已拒绝通过
                $baseQuery->whereNotNull('passed_at')->whereNull('rejected_at')->whereNull('transfer_course_id')->whereNull('postpone_course_id')->where('confirm',
                    0);
            } elseif (\Input::get("status") == "reject") {//拒绝
                $baseQuery->whereNull('passed_at')->whereNotNull('rejected_at')->whereNull('transfer_course_id')->whereNull('postpone_course_id');
            } elseif (\Input::get("status") == "to_transfer") // 待确认调班
            {
                $baseQuery->whereNull('passed_at')->whereNull('rejected_at')->whereNotNull('transfer_course_id')->whereNull('postpone_course_id')->whereNull('confirm');
            } elseif (\Input::get("status") == "transfered") // 已确认调班
            {
                $baseQuery->whereNull('passed_at')->whereNull('rejected_at')->whereNotNull('transfer_course_id')->whereNull('postpone_course_id')->where('confirm',
                    1);
            } elseif (\Input::get("status") == "no_transfer") // 已拒绝调班
            {
                $baseQuery->whereNull('passed_at')->whereNull('rejected_at')->whereNotNull('transfer_course_id')->whereNull('postpone_course_id')->where('confirm',
                    0);
            } elseif (\Input::get("status") == "to_postpone") //待确认延期
            {
                $baseQuery->whereNull('passed_at')->whereNull('rejected_at')->whereNull('transfer_course_id')->whereNotNull('postpone_course_id')->whereNull('confirm');
            } elseif (\Input::get("status") == "postponed") //已确认延期
            {
                $baseQuery->whereNull('passed_at')->whereNull('rejected_at')->whereNull('transfer_course_id')->whereNotNull('postpone_course_id')->where('confirm',
                    1);
            } elseif (\Input::get("status") == "no_postpone") //已拒绝延期
            {
                $baseQuery->whereNull('passed_at')->whereNull('rejected_at')->whereNull('transfer_course_id')->whereNotNull('postpone_course_id')->where('confirm',
                    0);
            }
        }
        $baseQuery->select(['application_course_result.id as id', 'application.id as application_id', 'application.name', 'degree.name as degree', 'tech_duty.name as tech_duty',
            'phone_number','organization','course.name as course','application_course_result.submitted_at as submitted_at','recommender','passed_at','rejected_at',
            'application.score','transfer_course_id','postpone_course_id', 'confirm']);
        $dto = Datatables_new::makeDto($baseQuery);
        foreach($dto['data'] as $item){
            if($item->passed_at==null){
                if($item->rejected_at==null){
                    if($item->transfer_course_id == null){
                        if ($item->postpone_course_id == null){
                            $item->status = "未审核";
                        } else {
                            if ($item->confirm == null)
                                $item->status = "待确认延期";
                            if ($item->confirm == '1')
                                $item->status = "已确认延期";
                            if ($item->confirm == '0')
                                $item->status = "已拒绝延期";
                        }
                    } else {
                        if($item->confirm == null)
                            $item->status = "待确认调班";
                        if($item->confirm == '1')
                            $item->status = "已确认调班";
                        if($item->confirm == '0')
                            $item->status = "已拒绝调班";
                    }
                }
                else $item->status = "拒绝";
            }
            else {
                if ($item->confirm == null)
                    $item->status = "待确认通过";
                if ($item->confirm == '1')
                    $item->status = "已确认通过";
                if ($item->confirm == '0')
                    $item->status = "已拒绝通过";
            }



            //score
            $Attr = \App\Models\Application::where("id",'=',$item->application_id)->get(['tech_duty_id','admin_duty_id','degree_id','region_id','org_rank_id']);
            $score = 0;
            $score += Tech_duty::where('id','=',$Attr[0]['attributes']['tech_duty_id'])->first(['score'])['attributes']['score'];
            $score += Admin_duty::where('id','=',$Attr[0]['attributes']['admin_duty_id'])->first(['score'])['attributes']['score'];
            $score += Degree::where('id','=',$Attr[0]['attributes']['degree_id'])->first(['score'])['attributes']['score'];
            $score += Region::where('id','=',$Attr[0]['attributes']['region_id'])->first(['score'])['attributes']['score'];
            $score += Org_rank::where('id','=',$Attr[0]['attributes']['org_rank_id'])->first(['score'])['attributes']['score'];

            $item->score = $score;
            //update database
            $application = \App\Models\Application::query()->findOrFail($item->application_id);
            $application->score = $score;
            $application->save();
        }
        return $dto;
    }


    public function create(Request $request)
    {
        $attr = $request->all();
        $application = Application::all();
        foreach($application as $value) {
            if ($value['phone_number'] == $attr["phone_number"]) {
                echo "<script> alert('该手机号已注册'); </script>";
                return redirect('/newuser_signup');
            }
        }
        $cachedCode = session()->get('code');
        if($cachedCode != null && $cachedCode === $attr["code"] && session()->get('phone_number_signup') == $attr["phone_number"]) {
            $new = new Application();
            $new['password'] = \Hash::make($attr["password"]);
            $new['phone_number'] = $attr["phone_number"];
            $new->save();
            session()->forget('code');
            session()->forget('phone_number_signup');
            echo "<script> alert('sucess');
                parent.location.href='/'; </script>";
        }
        else {
            echo "<script> alert('注册码错误');
                parent.location.href='/newuser_signup'; </script>";
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function preview($id,$course_name){
        $application = \App\Models\Application::query()->findOrFail($id);
        //$course = Course::query()->findOrFail($application['class_id']);
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
        //relation
        $relation = Relation::query()->where('application_id',$id)->first();
        //director
        $director = Director::query()->where('application_id',$id)->first();
        //tech_duty
        $tech_duty = Tech_duty::query()->findOrFail($application['tech_duty_id']);
        $admin_duty = Admin_duty::query()->findOrFail($application['admin_duty_id']);
        $degree = Degree::query()->findOrFail($application['degree_id']);
        $org_rank = Org_rank::query()->findOrFail($application['org_rank_id']);
        $region = Region::query()->findOrFail($application['region_id']);
        //multi
        $resumes = Resume::query()->where('application_id','=',$id)->get(['start_date','end_date','organization','title']);
        $educations = Education::query()->where('application_id','=',$id)->get(['start_date','graduation_date','school','major','degree']);
        $papers = Paper::query()->where('application_id','=',$id)->get(['name','journal','publish_date']);
        return view("application_admin.preview",['application'=>$application,"gender"=>$gender,"course_name"=>$course_name,"tech_duty"=>$tech_duty,"admin_duty"=>$admin_duty,"degree"=>$degree,"org_rank"=>$org_rank,"region"=>$region,
            "resumes"=>$resumes,"educations"=>$educations,"papers"=>$papers,'relation'=>$relation,'director'=>$director,'accommodation'=>$accommodation]);
    }

    public function electronic_table($id){
        $storage_path = storage_path();
        $extensions = array(".jpg",".png",".bmp",".pdf",".jpeg",".PNG",".JPG",".BMP",".PDF",".doc",".docx");
        $extension0 = ".jpg";
        foreach($extensions as $extension){
            $file_path = $storage_path . "/applications" . "/2015/" . $id . $extension;
            if(file_exists($file_path) == true){
                $extension0 = $extension;
                break;
            }
        }
        $file_path = $storage_path . '/applications/' . '2015/' .$id . $extension0;
        return response()->download($file_path);
    }
    public function electronic_view($id, $status){
        $item = \DB::table('application_course_result')->where('id',$id)->first();
        $application = \App\Models\Application::query()->where('id',$item->application_id)->first();
        $course = Course::query()->findOrFail($item->course_id);
        $tech_duty = Tech_duty::query()->where('id',$application->tech_duty_id)->first()->name;
        $degree = Degree::query()->where('id',$application->degree_id)->first()->name;
        $courses = Course::query()->get();
        return view('application_admin.electronic',['id'=>$id,'courses'=>$courses,'status'=>$status,'application'=>$application,'course'=>$course,'tech_duty'=>$tech_duty,'degree'=>$degree,'item'=>$item]);
    }

    public function pass($id){
        $application = \App\Models\Application::query()->findOrFail($id);
        $now = Carbon::now();
        $application->passed_at = $now;
        $application->rejected_at = null;
        $application->save();
        return redirect()->back()->with('success','已通过该学员申请');
    }
    public function reject($id){
        $application = \App\Models\Application::query()->findOrFail($id);
        $now = Carbon::now();
        $application->passed_at = null;
        $application->rejected_at = $now;
        $application->save();
        return redirect()->back()->with('success','已拒绝该学员申请');
    }
    public function operate ($id, Request $request)
    {
        $result = \DB::table('application_course_result')->where('id',$id);
        $operation = $request['operation'];
        $item['updated_at'] = Carbon::now();
        if ($operation == 'pass') {
            $item['passed_at'] = Carbon::now();
            $item['rejected_at'] = null;
            $item['transfer_course_id'] = null;
            $item['postpone_course_id'] = null;
            $item['confirm'] = null;
            $item['confirmed_at'] = null;
        }
        if ($operation == 'refuse') {
            $item['passed_at'] = null;
            $item['rejected_at'] = Carbon::now();
            $item['transfer_course_id'] = null;
            $item['postpone_course_id'] = null;
            $item['confirm'] = null;
            $item['confirmed_at'] = null;
        }
        if ($operation == 'transfer') {
            $course_id = $request['course_id'];
            $item['passed_at'] = null;
            $item['rejected_at'] = null;
            $item['transfer_course_id'] = $course_id;
            $item['postpone_course_id'] = null;
            $item['confirm'] = null;
            $item['confirmed_at'] = null;
        }
        if ($operation == 'postpone') {
            $course_id = $request['course_id'];
            $item['passed_at'] = null;
            $item['rejected_at'] = null;
            $item['transfer_course_id'] = null;
            $item['postpone_course_id'] = $course_id;
            $item['confirm'] = null;
            $item['confirmed_at'] = null;
        }
        $result->update($item);
        return redirect(route('application'))->with('success','审核成功');

    }
    public function index_cancel()
    {
        return view('application_admin.canceled');
    }

    public function indexDt_cancel(){
        $baseQuery = \DB::table('application')
            ->rightJoin('application_course_result','application_course_result.application','=','application.id')
            ->leftJoin('course','application_course_result.course_id','=','course.id')
            ->select(['application.id', 'application.name', 'phone_number','organization','course.name as course','submitted_at','recommender']);
        $dto = Datatables_new::makeDto($baseQuery);
        foreach($dto['data'] as $item){
            //$item->score = 10;
            $Attr = \App\Models\Application::where("id",'=',$item->id)->get(['tech_duty_id','admin_duty_id','degree_id','region_id','org_rank_id']);
            //dd($Attr);
            $score = 0;
            $score += Tech_duty::where('id','=',$Attr[0]['attributes']['tech_duty_id'])->first(['score'])['attributes']['score'];
            $score += Admin_duty::where('id','=',$Attr[0]['attributes']['admin_duty_id'])->first(['score'])['attributes']['score'];
            $score += Degree::where('id','=',$Attr[0]['attributes']['degree_id'])->first(['score'])['attributes']['score'];
            $score += Region::where('id','=',$Attr[0]['attributes']['region_id'])->first(['score'])['attributes']['score'];
            $score += Org_rank::where('id','=',$Attr[0]['attributes']['org_rank_id'])->first(['score'])['attributes']['score'];

            $item->score = $score;
            //update database
            $application = \App\Models\Application::query()->findOrFail($item->id);
            $application->score = $score;
            $application->save();
        }
        // dd($dto);
        return $dto;
        //return response()->json($dto);
    }

    public function index_unSubmit()
    {
        return view('application_admin.unSubmit');
    }

    public function indexDt_unSubmit(){
        $baseQuery = \DB::table('application')
            ->rightJoin('application_course_result','application_course_result.application_id','=','application.id')
            ->leftJoin('course','application_course_result.course_id','=','course.id')
            ->select(['application.id', 'application.name', 'phone_number','organization','course.name as course','application_course_result.updated_at'])
            ->whereNull('submitted_at');
        $dto = Datatables_new::makeDto($baseQuery);
        return $dto;
        //$datatables = new Datatables($baseQuery, $request);
        //$dto = $datatables->getResponseDto();//
        //need more calculate

        // dd($dto);
       // return response()->json($dto);
    }
//积分设置
    //技术职称
    public function tech_duty(){
        return view('application_admin.tech_duty');
    }
    public function tech_dutyDt(){
        $baseQuery = \DB::table('tech_duty')
            ->select(['id', 'name', 'score','comment']);

        //$datatables = new Datatables($baseQuery, $request);
        $dto = Datatables_new::makeDto($baseQuery);
        //need more calculate

        // dd($dto);
        return $dto;
    }
    public function TechEdit($id){
        $tech = Tech_duty::query()->findOrFail($id);
        return view('application_admin.techEdit',['tech_duty'=>$tech]);
    }
    public function TechUpdate($id, Request $request){
        $this->validate($request,[
            'name'=>'sometimes|required',
            'score'=>'sometimes|required|integer'
        ],[],[
            'name'=>'技术职称',
            'score'=>'积分值'
        ]);
        $updateData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        $tech = Tech_duty::query()->findOrFail($id);
        Tech_duty::unguard();
        $tech->update($updateData);
        return redirect()->back()->with('success','积分设置成功');
    }
    public function TechDelete($id){
        \DB::table('tech_duty')->delete($id);
        return redirect()->back()->with('success','删除成功');
    }
    public function TechAdd(){
        return view('application_admin.techAdd');
    }
    public function TechStore(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'score'=>'required|integer'
        ],[],[
            'name'=>'技术职称',
            'score'=>'积分值'
        ]);
        $storeData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        Tech_duty::unguard();
        Tech_duty::create($storeData);
       return redirect()->back()->with('success','添加积分成功');

    }
    //学位
    public function degree(){
        return view('application_admin.degree');
    }
    public function degreeDt(Request $request){
        $baseQuery = \DB::table('degree')
            ->select(['id', 'name', 'score','comment']);

       // $datatables = new Datatables($baseQuery, $request);
        $dto = Datatables_new::makeDto($baseQuery);
        //need more calculate

        // dd($dto);
        return $dto;
    }
    public function degreeEdit($id){
        $degree = Degree::query()->findOrFail($id);
        return view('application_admin.degreeEdit',['degree'=>$degree]);
    }
    public function degreeUpdate($id, Request $request){
        $this->validate($request,[
            'name'=>'sometimes|required',
            'score'=>'sometimes|required|integer'
        ],[],[
            'name'=>'学位',
            'score'=>'积分值'
        ]);
        $updateData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        $degree = Degree::query()->findOrFail($id);
        Degree::unguard();
        $degree->update($updateData);
        return redirect()->back()->with('success','积分设置成功');
    }
    /*public function TechDelete($id){
        \DB::table('tech_duty')->delete($id);
        return redirect()->back()->with('success','删除成功');
    }*/
    public function degreeAdd(){
        return view('application_admin.degreeAdd');
    }
    public function degreeStore(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'score'=>'required|integer'
        ],[],[
            'name'=>'学位',
            'score'=>'积分值'
        ]);
        $storeData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        Degree::unguard();
        Degree::create($storeData);
        return redirect()->back()->with('success','添加积分成功');
    }

    //医院级别
    public function org_rank(){
        return view('application_admin.org_rank');
    }
    public function org_rankDt(Request $request){
        $baseQuery = \DB::table('org_rank')
            ->select(['id', 'name', 'score','comment']);

        //$datatables = new Datatables($baseQuery, $request);
        $dto = Datatables_new::makeDto($baseQuery);
        //need more calculate

        // dd($dto);
        return $dto;
    }
    public function org_rankEdit($id){
        $org_rank = Org_rank::query()->findOrFail($id);
        return view('application_admin.org_rankEdit',['org_rank'=>$org_rank]);
    }
    public function org_rankUpdate($id, Request $request){
        $this->validate($request,[
            'name'=>'sometimes|required',
            'score'=>'sometimes|required|integer'
        ],[],[
            'name'=>'医院级别',
            'score'=>'积分值'
        ]);
        $updateData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        $admin_duty = Org_rank::query()->findOrFail($id);
        Org_rank::unguard();
        $admin_duty->update($updateData);
        return redirect()->back()->with('success','积分设置成功');
    }
    /*public function TechDelete($id){
        \DB::table('tech_duty')->delete($id);
        return redirect()->back()->with('success','删除成功');
    }*/
    public function org_rankAdd(){
        return view('application_admin.org_rankAdd');
    }
    public function org_rankStore(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'score'=>'required|integer'
        ],[],[
            'name'=>'医院级别',
            'score'=>'积分值'
        ]);
        $storeData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        Org_rank::unguard();
        Org_rank::create($storeData);
        return redirect()->back()->with('success','添加积分成功');
    }
    //行政职务
    public function admin_duty(){
        return view('application_admin.admin_duty');
    }
    public function admin_dutyDt(Request $request){
        $baseQuery = \DB::table('admin_duty')
            ->select(['id', 'name', 'score','comment']);

        //$datatables = new Datatables($baseQuery, $request);
        $dto = Datatables_new::makeDto($baseQuery);//
        //need more calculate

        // dd($dto);
        return $dto;
    }
    public function admin_dutyEdit($id){
        $admin_duty = Admin_duty::query()->findOrFail($id);
        return view('application_admin.admin_dutyEdit',['admin_duty'=>$admin_duty]);
    }
    public function admin_dutyUpdate($id, Request $request){
        $this->validate($request,[
            'name'=>'sometimes|required',
            'score'=>'sometimes|required|integer'
        ],[],[
            'name'=>'行政职务',
            'score'=>'积分值'
        ]);
        $updateData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        $admin_duty = Admin_duty::query()->findOrFail($id);
        Admin_duty::unguard();
        $admin_duty->update($updateData);
        return redirect()->back()->with('success','积分设置成功');
    }
    /*public function TechDelete($id){
        \DB::table('tech_duty')->delete($id);
        return redirect()->back()->with('success','删除成功');
    }*/
    public function admin_dutyAdd(){
        return view('application_admin.admin_dutyAdd');
    }
    public function admin_dutyStore(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'score'=>'required|integer'
        ],[],[
            'name'=>'行政职务',
            'score'=>'积分值'
        ]);
        $storeData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        Admin_duty::unguard();
        Admin_duty::create($storeData);
        return redirect()->back()->with('success','添加积分成功');
    }
    //地域
    public function region(){
        return view('application_admin.region');
    }
    public function regionDt(Request $request){
        $baseQuery = \DB::table('region')
            ->select(['id', 'name', 'score','comment']);

        //$datatables = new Datatables($baseQuery, $request);
        $dto = Datatables_new::makeDto($baseQuery);
        //need more calculate

        // dd($dto);
        return $dto;
    }
    public function regionEdit($id){
        $region = Region::query()->findOrFail($id);
        return view('application_admin.regionEdit',['region'=>$region]);
    }
    public function regionUpdate($id, Request $request){
        $this->validate($request,[
            'name'=>'sometimes|required',
            'score'=>'sometimes|required|integer'
        ],[],[
            'name'=>'地域',
            'score'=>'积分值'
        ]);
        $updateData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        $region = Region::query()->findOrFail($id);
        Region::unguard();
        $region->update($updateData);
        return redirect()->back()->with('success','积分设置成功');
    }
    /*public function TechDelete($id){
        \DB::table('tech_duty')->delete($id);
        return redirect()->back()->with('success','删除成功');
    }*/
    public function regionAdd(){
        return view('application_admin.regionAdd');
    }
    public function regionStore(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'score'=>'required|integer'
        ],[],[
            'name'=>'地域',
            'score'=>'积分值'
        ]);
        $storeData = array_only($request->all(),[
            'name',
            'score',
            'comment'
        ]);
        Region::unguard();
        Region::create($storeData);
        return redirect()->back()->with('success','添加积分成功');
    }
}