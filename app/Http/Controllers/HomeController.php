<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
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
    if(isset($past)
    && $past->start_time != null 
    && $past->end_time =null 
    && $past->date != $now){
        $endtime->update([
            'end_time' => '23:59:59',
        ]);
    }
    
    //日を跨いだ際の出勤を継続するためにstrat_timeに'00:00:00'を
    //attendanceテーブルにデータがあると実行しない = '00:00:00'を格納

if (isset($past) && ($starttime) && $past->end_time == '23:59:59' && empty($attendance)){
        $starttime = Attendance::create([
            'user-id' => $user->id,
            'date' => Carabon::today(),
            'strat_time' =>'00:00:00',
        ]);
    }
    if ($attendance !== null) { // 勤務開始ボタンを押した場合
            
        if ($attendance->end_time === null ) { // 勤務終了ボタンを押した場合
            $rest = Rest::where('attendance_id', $attendance->id)->latest()->first();
                if ($rest !== null) { // 休憩開始ボタンを押した場合
                    if ($rest->rests_end !== null) { // 休憩終了ボタンを押した場合
                        $work_out = true;
                        $rest_in = true;
                    } else { // 休憩中の場合
                        $work_out = true;
                        $rest_out = true;
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

public function getAttendance(Request $request)
    {
        if ($request->page) {
            $date = $request->date; // 現在指定している日付を取得
        } else {
            $date = Carbon::today()->format("Y-m-d");
        }
        $attendances = Attendance::whereDate('date', $date)->orderBy('user_id', 'asc')->paginate(5);
        $attendances->appends(compact('date')); //日付を渡す

        foreach ($attendances as $attendance) {
            foreach ($attendances as $attendance) {
                // このループのattendanceのidを持つrestデータを取得
                // 休憩時間
                $rests = $attendance->rests;
                $total_rest_time = 0;
                foreach ($rests as $rest) {
                    $total_rest_time = $total_rest_time + strtotime($rest->rests_end) - strtotime($rest->rests_strat);
                }
                $rest_hour = floor($total_rest_time / 3600); // 時を算出
                $rest_minute = floor(($total_rest_time / 60) % 60); // 分を算出
                $rest_minute_c = floor(($rest_minute / 5)) * 5; //分を5分単位で切り下げ
                $rest_seconds = floor($total_rest_time % 60); //秒を算出

                // sprintf関数で第一引数に指定したフォーマットで文字列を生成
                $attendance->rest_time = sprintf('%2d時間%02d分', $rest_hour, $rest_minute_c, $rest_seconds);
                // 拘束時間
                $restraint_time = strtotime($attendance->end_time) - strtotime($attendance->start_time);
                //拘束時間と合計休憩時間の差
                $total_work_time = 0;
                $total_work_time = $total_work_time + $restraint_time - $total_rest_time;
                $work_hour = floor($total_work_time / 3600);
                $work_minute = floor(($total_work_time / 60) % 60);
                $work_minute_c = floor(($work_minute / 5)) * 5;
                $work_second = floor($total_work_time % 60);
                $attendance->work_time = sprintf('%2d時間%02d分', $work_hour, $work_minute_c, $work_second);
            }
        }

        return view('attendance', [
            'attendances' => $attendances,
            'today' => $date
        ]);
    }

    public function changeDate(Request $request)
    {
        // 一日前（'<'ボタン）
        if ($request->input('before') == 'before') {
            $date = date('Y-m-d', strtotime('-1day', strtotime($request->input('date'))));
            $attendances = Attendance::whereDate('date', $date)->orderBy('user_id', 'asc')->paginate(5);
        }
        // 一日後（'>'ボタン）
        if ($request->input('next') == 'next') {
            $date = date('Y-m-d', strtotime('+1day', strtotime($request->input('date'))));
            $attendances = Attendance::whereDate('date', $date)->orderBy('user_id', 'asc')->paginate(5);
        }
        $attendances->appends(compact('date')); //日付を渡す

        foreach ($attendances as $attendance) {
            // このループのattendanceのidを持つrestデータを取得
            // 休憩時間
            $rests = $attendance->rests;
            $total_rest_time = 0;
            foreach ($rests as $rest) {
                $total_rest_time = $total_rest_time + strtotime($rest->rests_end) - strtotime($rest->rests_strat);
            }
            $rest_hour = floor($total_rest_time / 3600); // 時を算出
            $rest_minute = floor(($total_rest_time / 60) % 60); // 分を算出
            $rest_minute_c = floor(($rest_minute / 5)) * 5; //分を5分単位で切り下げ
            $rest_seconds = floor($total_rest_time % 60); //秒を算出
            $attendance->rest_time = sprintf('%2d時間%02d分', $rest_hour, $rest_minute_c, $rest_seconds);
            // 拘束時間
            $restraint_time = strtotime($attendance->end_time) - strtotime($attendance->start_time);
            //拘束時間と合計休憩時間の差
            $total_work_time = 0;
            $total_work_time = $total_work_time + $restraint_time - $total_rest_time;
            $work_hour = floor($total_work_time / 3600);
            $work_minute = floor(($total_work_time / 60) % 60);
            $work_minute_c = floor(($work_minute / 5)) * 5;
            $work_second = floor($total_work_time % 60);
            $attendance->work_time = sprintf('%2d時間%02d分', $work_hour, $work_minute_c, $work_second);
        }

        return view('attendance')->with([
            'attendances' => $attendances,
            'today' => $date
        ]);
    }
}