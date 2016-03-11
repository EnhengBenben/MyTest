<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Course;
use Illuminate\Http\Request;

use App\Models\Application;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class WeChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $rules = [
            'phone_number' => 'required',
            'password' => 'required'
        ];
        $validator = $this->getValidationFactory()->make($request->all(), $rules, [], []);
        if ($validator->fails()) {
            return ('input error');
        }
        $ok = \Auth::attempt(['phone_number' => $request['phone_number'], 'password' => $request['password']]);
        if ($ok) {
            $applier = Application::where('phone_number',$request['phone_number'])->get();
            $id = $applier[0]['id'];
            return response($id);
        }
        return response("false");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $attr = $request->all();
        $application = Application::all();
        foreach($application as $value) {
            if ($value['phone_number'] == $attr["phone_number"]) {
                return response('used');
            }
        }
        $cachedCode = $request->session()->get('code');
        if($cachedCode != null && $cachedCode === $attr["code"] && session()->get('phone_number_signup') == $attr["phone_number"]) {
            $new = new Application();
            $new['password'] = \Hash::make($attr["password"]);
            $new['phone_number'] = $attr["phone_number"];
            $new->save();
            session()->forget('code');
            session()->forget('phone_number_signup');
            return response("ok");
        }
        else {
            return response("false");
        }
    }

    public function update(Request $request)
    {
        $attr = $request->all();
        $cachedCode = $request->session()->get('code');
        $new = Application::firstOrNew(['phone_number' => $attr["phone_number"]]);
        if($cachedCode != null && $cachedCode === $attr["code"] && session()->get('phone_number_signup') == $attr["phone_number"]) {
            $new['password'] = \Hash::make($attr["password"]);
            $new['phone_number'] = $attr["phone_number"];
            $new->save();
            session()->forget('code');
            session()->forget('phone_number_signup');
            return response("ok");
        }
        else {
            return response("false");
        }
    }

    public function accept(Request $request)
    {
        $id = $request->result_id;
        \DB::table('application_course_result')->where('id',$id)->update(['confirmed_at' => date("Y-m-d H:i:s") ,
            'confirm' => 1]);
        return response('ok');
    }

    public function reject(Request $request)
    {
        $id = $request->result_id;
        \DB::table('application_course_result')->where('id',$id)->update(['confirmed_at' => date("Y-m-d H:i:s") ,
            'confirm' => 0]);
        return response('ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $now = strtotime(date("Y-m-d"));
        $id = $request->applier_id;
        $results = \DB::table('application_course_result')->where('application_id',$id)->get();
        foreach ($results as $result) {
            $course = Course::findOrNew($result->course_id);
            $result->course = $course;
            $announcetime = strtotime($course->announcement_date);
            if($now < $announcetime)
                $result -> isannouncement = 0;//未公布
            else {
                if($result->passed_at == null && $result->rejected_at == null && $result->transfer_course_id == null &&
                    $result->postpone_course_id == null) {
                    $result_id = $result->id;
                    \DB::table('application_course_result')->where('id',$result_id)->update(['rejected_at' => date("Y-m-d H:i:s")]);
                    $result->rejected_at = date("Y-m-d H:i:s");
                }
                $result -> isannouncement = 1;
                if($result->rejected_at == null &&$result->passed_at == null) {
                    if ($result->transfer_course_id != null) {
                        $course = Course::findOrNew($result->transfer_course_id);
                        $result->transfer_course = $course;
                    } elseif ($result->postpone_course_id != null) {
                        $course = Course::findOrNew($result->postpone_course_id);
                        $result->postpone_course = $course;
                    }
                }
            }
        }
        return response()->json($results);
    }


    public function course(Request $request)
    {
        //课程信息
        $id = $request->course_id;
        $course['info'] = Course::findOrNew($id);
        $course['attachment_count'] = Attachment::where('class_id',$id)->count();
        $course['attachment'] = Attachment::where('class_id',$id)->get();
        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
