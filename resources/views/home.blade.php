@extends('layouts.app')

@section('content')
<style>
    .home-btn{
        width: 50%;
        height: 60%;
        text-align: center;
    }

</style>
<p id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="text" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}さんお疲れ様です！
                                </p>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- <div class="card-header">{{ __('Dashboard') }}</div> -->
                <div class="card-body-home">
<!-- 勤務開始 -->
                <form action="/start" method="POST" class="home-btn A">
                    @csrf
                    <input type="submit" class="home-btn"
                         value="勤務開始" id="work_in" <?php if ($btn['work_in'] === false) { ?> disabled <?php } ?>>
                    </form>
                    <form action="/end" method="POST" class="home-btn">
                        @csrf
                    <input type="submit" class="home-btn"
                        value="勤務終了"id="work_out" <?php if ($btn['work_out'] === false) { ?> disabled <?php } ?>>
                    </form>
                    <form action="/rest_start" method="POST" class="home-btn B">
                        @csrf
                    <input type="submit" class="home-btn"
                        value="休憩開始"id="rest_in" <?php if ($btn['rest_in'] === false) { ?> disabled <?php } ?>>
                    </form>
                    <form action="/rest_end" method="POST" class="home-btn C">
                        @csrf
                    <input type="submit" class="home-btn"
                        value="休憩終了"id="rest_out" <?php if($btn['rest_out'] === false) { ?> disabled <?php } ?>>
                            </form>

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
