<!DOCTYPE html>
<html>
<head>
    {{--@yield
          age1: 该区块名称
          ahe2: 默认值
    --}}
    <title>@yield('title','Sample') - Laravel小项目 </title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
@include('layouts._header')

<div class="container">
    <div class="col-md-offset-1 col-md-10">
        @include('shared.messages')
        @yield('content')
        @include('layouts._footer')
    </div>
</div>
</body>
</html>