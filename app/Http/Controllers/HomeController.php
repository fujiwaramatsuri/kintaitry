<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rset;
use Carbon\Carbon;

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
        // ボタン活性false非活性
        $work_in = false;
        $work_out = false;
        $rest_in = false;
        $rest_out = false;

        $user = Auth::user();//Auth=現ユーザー
        $today = Carbon::today();//->fomat('Y-m-d');
        $now = Carbon::now();//->format('Y-m-d');
        $attendance = Attendance::where('user_id',$user->id)->where('date',$today)->first();
        $past = Attendance::where('user_id',$user->id)->where('date','<', $today)->first();
        $endtime = Attendance::where('user_id',$user->id)->latest()->first();
        $starttime = Attendance::where('user_id',$user->id)->latest()->first();

        // 出勤したまま日を跨ぐとend_timeを'23:59:59'に更新
    if($past->start_time != null && $past->end_time =null && $past->date != $now){$endtime->update([
        'end_time' => '23:59:59',
    ]);
    }
    
    //日を跨いだ際の出勤を継続するためにstrat_timeに'00:00:00'を
    //attendanceテーブルにデータがあると実行しない = '00:00:00'を格納

if (($starttime) && $past->end_time == '23:59:59' && empty($attendance)){
        $starttime = Attendance::create([
            'user-id' => $user->id,
            'date' => Carabon::today(),
            'strat_time' =>'00:00:00',
        ]);
    }
    if ($attendance != null) { // 勤務開始ボタンを押した場合
            if ($attendance['end_time'] != null) { // 勤務終了ボタンを押した場合
            } else { // 勤務中の場合
                $rest = Rest::where('attendance_id', $attendance->id)->latest()->first();
                if ($rest != null) { // 休憩開始ボタンを押した場合
                    if ($rest['breakout_time'] != null) { // 休憩終了ボタンを押した場合
                        $work_out = true;
                        $rest_in = true;
                    } else { // 休憩中の場合
                        $work_out = true;
                    }
                } else { // 休憩中ではない場合
                    $work_out = true;
                    $rest_in = true;
                }
            }
        } else { // 当日初めてログインした場合
            $work_in = true;
        }
    
$btn = [
    'work_in' => $work_in,
    'work_out' => $work_out,
    'rest_in' => $rest_in,
    'rest_out' => $rest_out,
];
        return view('home', ['btn' => $btn]);
    }
    // 勤怠開始
    public function start()
    {
        $user = Auth::user();//ログインしているユーザー
        $attendance = [//$attendanceに入れる中身
            'user_id' => $user->id,
            'date' => Carbon::today(), //今日の日付
            'start_time' => Carbon::now(), //現時刻
            'end_time' => null,
        ];
        DB::table('attendances')->insert($attendance);//tableのattendancesに$attendanceを入れる
        return redirect('/home');
    }
    //勤怠終了
    public function end()
    {
        // Log::info('最初');
        $user = Auth::user();
        $attendance = DB::table('attendances')->where('user_id', $user->id)
                    ->where('date', Carbon::today())->latest();//sqlの発行
        $timestamp = Carbon::now();
        $attendance->update([
            'end_time' => $timestamp,
        ]);
        // DB::table('attendances')->insert($timestamp);
        return redirect('/home');
}

// 休憩開始
public function rest_start()
    {
        $attendance = Auth::user();
        
        $rest = [
            // 'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'date' => Carbon::today(), //今日の日付
            'rests_strat' => Carbon::now(), //現時刻
            'rests_end' => null,
        ];
        DB::table('rests')->insert($rest);
        return redirect('/home');
    }
public function rest_end()
    {
        $attendance = Auth::user();//現ユーザー
        $rests = DB::table('rests')->where('attendance_id',$attendance->id)->where('date', Carbon::today())->latest();
        $timestamp =Carbon::now();
        $rests->update([
            'rests_end' => $timestamp,
        ]);
        return redirect('/home');
    }

public function confilm()
    {
        $items = Attendance::first();//モデルから取ってくる
        // $item = Rest::first();
        // $item = DB::table('attendances')->get();
        // $item = DB::table('users')->get();
        // $item = DB::select('select * from users' );
        // $item = DB::select('select * from attendances');
        // dd($item);
        // $item = DB::select('select * from rests');
        // dd($rest);
        $items = attendance::Paginate(5);
        return view('confilm',['item' => $items]);
    }
}
