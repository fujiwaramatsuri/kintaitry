<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    /* ログインテキストボックス */
.form-control {
    display: block;
    appearance: none;
    outline: 0;
    border: 1px solid fade(white, 40%);
    background-color: fade(white, 20%);
    width: 250px;
    border-radius: 3px;
    padding: 10px 15px;
    margin: 0 auto 10px auto;
    display: block;
    text-align: center;
    font-size: 18px;
    }
    /* ログインタイトル */
.card-header {
        text-align:center;
        margin-top:130px;
        margin-bottom:20px;
    }
    /* ボタン */
.btn-primary {
        display: flex;
        margin:auto;
		appearance:none;
		outline: 0;
		background-color:;
		border: 0;
		padding: 10px 15px;
		color: @prim;
		border-radius: 3px;
		width: 250px;
		cursor: pointer;
		font-size: 18px;
		transition-duration: 0.25s;
        justify-content: center;
    }
.nav-item{
    /* display: block; */
    text-align:center;
    }
    /* ヘッダー */
.navbar-nav{
  text-align: right;
}
.nav-link{
  text-align: center;
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 70px;
    padding: 0 30px;
    background-color: #fff;
    position: sticky;
    left: 0;
    top: 0;
    z-index: 1;
}
.header-nav {
    /* display: flex; */
    text-align: right;
    margin: 0 0 0 auto;
}
.header-nav-list {
    /* display: flex; */
    font-weight: bold;
    text-align: right;
    /* margin: 0 0 0 auto; */
    /* border:1px solid red !important; */
}
.header-nav-item {
    /* display: flex; */
    /* margin: 0  0 0 auto; */
    margin-right: 30px;
}
.nav-linkA {
    text-align: center;
}
/* ホームボタン */
.card-body-home{
    display: flex;
    flex-wrap: wrap;
    margin: auto;
    width: 900px;
    height: 500px;
    padding: 10px 15px;
}
.home-btn {
    width: 40%;
    height: 45%;
	margin: auto;
    font-size: 30px;
}

/*日付一覧*/
.arrow{

}
.change-date{
  display: flex;
  align-items: baseline;/*  アイテムの縦方向 */
  justify-content: space-evenly;/*アイテムの水平方向 */
}
.table{
  margin: auto;
  height: 60%;
  width: -webkit-fill-available;
}
.table-head-ttl{
  align-items: center;
  background-color: #289ADC;
  color: white;
  padding: 5px 40px;
  
}
.table-item{
  width: 10%;
  text-align: center;
  border-bottom: dotted;
}
.justify-content-center{
  /* padding: auto;
  margin-block: auto;
  -webkit-text-orientation: upright;
  writing-mode: vertical-lr;
} */
 * {
 /* border:1px solid red !important; */
}
    </style>
</head>
<body>
    
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <span class="nav-itemA">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                                </span>
                            @endif

                            @if (Route::has('register'))
                                <span class="nav-itemA">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('会員登録') }}</a>
                                </span>
                            @endif

                        @else
                        
                            <span class="nav-itemA">
                               <a href="/home">ホーム</a>
                               </span>
</a></span>
<!-- 日付一覧 -->
                               <span class="nav-itemA">
                               <a href="/attendance">日付一覧</a>
                               </span>
<!-- ログアウト -->
                                <span class="nav-itemA" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('ログアウト') }}
                                    </a>
</span>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
