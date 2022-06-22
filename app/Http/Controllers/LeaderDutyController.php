<?php

namespace App\Http\Controllers;

use App\Models\LeaderDuty;
use App\Models\Leader as Leader;
use App\Models\Week as Week;
use App\Models\News as News;
use Illuminate\Http\Request;
use App\Traits\MediaTraits;
use Illuminate\Support\Facades\Auth;

class LeaderDutyController extends Controller
{
    use MediaTraits;

    public function index()
    {
        $leader= Leader::where('supervisor_id' ,Auth::id())->latest()->orderBy('created_at', 'DESC')->paginate(6);
        $week_id= Week::latest('id')->first()->id;
        $news= News::where('week_id', $week_id)->orderBy('created_at', 'DESC')->paginate(3);
        return view('LeaderDuty', compact('leader', 'news'));
    }


    public function create()
    {
       //
    }


    public function store(Request $request)
    {
        $request->validate([

            'leader_id'=>'required',
            'team_final_mark'=>'required',
            'current_team_members'=>'required',
            'follow_up_post'=>'required',
            'support_post'=>'required',
            'leader_reading'=>'required',
            'elementary_mark'=>'required',
            'final_mark'=>'required',
            'audit_final_mark'=>'required',
            'withdrawn_ambassadors'=>'required',
            'leader_message_1' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'leader_message_2' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'leader_message_3' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'leader_reply_message' => 'image|mimes:jpeg,png,jpg,gif,svg',

        ]);



        if ($request->hasFile('leader_message_1')){
            $leader_message_1 = $this->createMedia($request->file('leader_message_1'));
        } else {$leader_message_1= "null";}

        if ($request->hasFile('leader_message_2')){
            $leader_message_2 = $this->createMedia($request->file('leader_message_2'));
        } else {$leader_message_2= "null";}

        if ($request->hasFile('leader_message_3')){
            $leader_message_3 = $this->createMedia($request->file('leader_message_3'));
        } else {$leader_message_3= "null";}

        if ($request->hasFile('leader_reply_message')){
            $leader_reply_message = $this->createMedia($request->file('leader_reply_message'));
        } else {$leader_reply_message= "null";}



         $follow_up_array = serialize(array([
            "follow_up_post" =>"$request->follow_up_post",
            "follow_up_standard_1" => "$request->follow_up_standard_1",
            "follow_up_standard_2" => "$request->follow_up_standard_2",
            "follow_up_standard_3" => "$request->follow_up_standard_3",
            "follow_up_standard_4" => "$request->follow_up_standard_4",
            "follow_up_standard_5" => "$request->follow_up_standard_5",
            "follow_up_standard_6" => "$request->follow_up_standard_6",
            "follow_up_standard_7" => "$request->follow_up_standard_7",
            "follow_up_standard_8" => "$request->follow_up_standard_8",
            "follow_up_standard_9" => "$request->follow_up_standard_9",
            "follow_up_standard_10" => "$request->follow_up_standard_10"
        ]));

         $support_array = serialize(array([
            "support_post" =>"$request->support_post",
            "support_standard_1" => "$request->support_standard_1",
            "support_standard_2" =>"$request->support_standard_2",
            "support_standard_3" =>"$request->support_standard_3",
            "support_standard_4" =>"$request->support_standard_4",
            "support_standard_5" =>"$request->support_standard_5",
            "support_standard_6" =>"$request->support_standard_6",
            "support_standard_7" =>"$request->support_standard_7"]));

         $news_array = serialize(array([
            "leader" => "$request->news_leader",
            "discussion" => "$request->news_discussion",
            "writing"=> "$request->news_writing" ]));

         $elementary_mark_array = serialize(array([
            "elementary_mark"=> "$request->elementary_mark",
            "elementary_standard_1" => "$request->elementary_standard_1",
            "elementary_standard_2" => "$request->elementary_standard_2",
            "elementary_standard_3" => "$request->elementary_standard_3",
            "elementary_standard_4" => "$request->elementary_standard_4",
            "elementary_standard_5" => "$request->elementary_standard_5"]));

         $audit_final_mark_array = serialize(array([
            "audit_final_mark" => $request->audit_final_mark,
            "leader_message_1" => $leader_message_1,
            "leader_message_2" => $leader_message_2,
            "leader_message_3" => $leader_message_3,
            "leader_reply_message" => $leader_reply_message ]));

         $withdrawn_ambassadors_array = serialize(array(
            "withdrawn_ambassadors" => "$request->withdrawn_ambassadors",
            "defective_num" => "$request->num_defective"));

          $leaderduty= LeaderDuty::create([
            'leader_id'=> $request->leader_id ,
            'week_id'=> Week::latest('id')->first()->id,
            'supervisor_id'=>Auth::id(),
            'team_final_mark' =>$request->team_final_mark,
            'current_team_members' =>$request->current_team_members,
            'follow_up_post'=>$follow_up_array,
            'support_post' =>$support_array,
            'news' =>$news_array,
            'leader_reading' =>$request->leader_reading,
            'elementary_mark' =>$elementary_mark_array,
            'final_mark' =>$request->final_mark,
            'audit_final_mark' =>$audit_final_mark_array,
            'withdrawn_ambassadors' =>$withdrawn_ambassadors_array
        ]);

              // dd($leaderduty);

        return redirect()->route('index')->with('message', 'Your Entry Saved');
    }


    public function show( leaderduty $leaderduty,Request $request )
    {
        $week = Week::latest()->first();
        $leaderduty = LeaderDuty::where('week_id', $week->id)->where('supervisor_id', Auth::id())->get();
        foreach ($leaderduty as $duty) {
            $duty->follow_up_post = unserialize($duty->follow_up_post);
            $duty->support_post = unserialize($duty->support_post);
            $duty->news_leader = unserialize($duty->news_leader);
            $duty->elementary_mark = unserialize($duty->elementary_mark);
            $duty->audit_final_mark = unserialize($duty->audit_final_mark);
            $duty->withdrawn_ambassadors = unserialize($duty->withdrawn_ambassadors);
        }


        foreach ($leaderduty as $duty) {
            print_r($duty->audit_final_mark[0]['audit_final_mark']);
            echo "<br> --------------- <br>";
        }


        //    return view('result')->with('leaderduty',$leaderduty);



    }


    public function edit(leaderduty $leaderduty)
    {

        //
    }


    public function update(Request $request, leaderduty $leaderduty)
    {
        //
    }


    public function destroy(leaderduty $leaderduty)
    {
        //
    }
}
