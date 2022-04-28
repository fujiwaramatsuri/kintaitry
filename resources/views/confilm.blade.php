@extends('layouts.app')

@section('content')
<style>
    th {
      background-color: #289ADC;
      color: white;
      padding: 5px 40px;
    }
    tr:nth-child(odd) td{
      background-color: #FFFFFF;
    }
    td {
      padding: 25px 40px;
      background-color: #EEEEEE;
      text-align: center;
    }
</style>
@section('title','日付一覧')

@section('content')
<table>
  <tr>
    <th>名前</th>
    <th>勤務開始</th>
    <th>勤務終了</th>
    <th>休憩開始</th>
    <th>休憩終了</th>
  </tr>
  @foreach ($item as $items)
  <tr>
    <td>
      {{$items->user_id}}
    </td>
    <td>
      {{$items->start_time}}
    </td>
    <td>
      {{$items->end_time}}
    </td>
    <td>
      {{$items->rests_strat}}
    </td>
    <td>
      {{$items->rests_end}}
    </td>
  </tr>
  @endforeach
</table>
@if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- {{ __('You are logged in!') }} -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
