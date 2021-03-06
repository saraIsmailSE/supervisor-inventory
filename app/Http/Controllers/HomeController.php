<?php

namespace App\Http\Controllers;
use App\Models\Leader as Leader;
use App\Models\LeaderDuty;
use App\Models\Week as Week;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $leader= Leader::where('supervisor_id' ,Auth::id())->latest()->orderBy('created_at', 'DESC')->paginate(6);
        $mark= LeaderDuty::where('supervisor_id' ,Auth::id())
        ->where('leader_id', Leader::where('supervisor_id' ,Auth::id())->latest()->first()->id)
        ->where('week_id', Week::latest('id')->first()->id)
        ->orderBy('created_at', 'DESC')->paginate(6);

        // print_r($data);
        return view('front' , compact ('leader', 'mark'));
    }
}
