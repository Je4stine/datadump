<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ URL::to('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('css/animate.min.css') }}" rel="stylesheet">
    <script src="{{ URL::to('tinymce/tinymce.min.js') }}"></script>

    <!-- Custom styling plus plugins -->
    <link href="{{ URL::to('magicsuggest/magicsuggest.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/maps/jquery-jvectormap-2.0.3.css') }}" />
    <link href="{{ URL::to('css/icheck/flat/green.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('css/floatexamples.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ URL::to('js/jquery.min.js') }}"></script>

    <script src="{{ URL::to('js/nprogress.js') }}"></script>


    <script src="{{ URL::to('magicsuggest/magicsuggest.js') }}"></script>
    <!--
    phone selector js and css
     -->
    <link href="{{ URL::to('intl-tel-input-master/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ URL::to('intl-tel-input-master/build/js/intlTelInput.js') }}"></script>

    <script src="{{ URL::to('rating/jquery.MetaData.js') }}"></script>
    <link href="{{ URL::to('rating/jquery.rating.css') }}" rel="stylesheet">
    <script src="{{ URL::to('rating/jquery.rating.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::to('css/star-rating.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('css/chat.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('css/throbber.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <script src="{{ URL::to('js/local.js') }}"></script>
    <link href="{{ URL::to('css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">


    <script src="{{ URL::to('js/star-rating.js') }}" type="text/javascript"></script>
    <script src="{{ URL::to('js/highcharts/js/highcharts.js') }}" type="text/javascript"></script>
    <script src="{{ URL::to('js/highcharts/js/highcharts-more.js') }}" type="text/javascript"></script>
    <script src="{{ URL::to('js/jquery.toaster.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <h3>Dashboard</h3>
    <ul class="nav nav-tabs">
        <?php
        $no=0;
        $url = Request::url();
        $math_no = 0;
        ?>
        @foreach($menus as $menu)
            @if($menu->type=='single')
                <?php
                $no++;
                if($url==URL::to($menu->menus->url)){
                    $math_no = "tab_".$menu->slug;
                }
                ?>
                    <li onclick="getForPage('{{ URL::to($menu->menus->url) }}');" class="{{ $url==URL::to($menu->menus->url) ? "active":"" }}"><a data-toggle="tab" href="#tab_{{ $menu->slug }}">{{ $menu->menus->label }}&nbsp;&nbsp;<span id="{{ $menu->slug }}"></span></a></li>
            @endif
        @endforeach
            @if($navbar_menu->account)
                @foreach($navbar_menu->account as $rmenu)
                    @if(count($rmenu->children)>0 && Auth::user())
                        <?php
                        $label = str_replace("{name}",Auth::user()->name,$rmenu->label);
                        if($rmenu->label=="{email}"){
                            $label =  str_replace("{email}",Auth::user()->email,$rmenu->label);
                        }

                        ?>
                            
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">{{ $label }}<span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                        @foreach($rmenu->children as $child)
                                        <?php
                                        if($child->url=="{website}"){
                                            $www_url = $www->home_url;
                                            $ww_array = explode('/',$www_url);
                                            unset($ww_array[count($ww_array)-1]);
                                            $child->url=implode('/',$ww_array);
                                        }
                                        ?>
                                            <li><a href="{{ URL::to($child->url) }}"><i class="fa {{ $child->icon }}"></i> {{ $child->label }}</a></li>
                                    @endforeach
                                        </ul>
                                      </li>
                                
                                    
                                
                    @endif
                @endforeach
            @endif
            @if(Auth::user())
            @include('includes.messages')
                @endif
    </ul>

    <div class="tab-content">
        <div id="{{ $math_no }}" class="tab-pane fade in active">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
<script src="{{ URL::to('js/progressbar/bootstrap-progressbar.min.js') }}"></script>
<script src="{{ URL::to('js/nicescroll/jquery.nicescroll.min.js') }}"></script>
<!-- icheck -->
<script src="{{ URL::to('js/icheck/icheck.min.js') }}"></script>
<!-- daterangepicker -->
<script type="text/javascript" src="{{ URL::to('js/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::to('js/datepicker/daterangepicker.js') }}"></script>
<!-- chart js -->
<script src="{{ URL::to('js/chartjs/chart.min.js') }}"></script>

<script src="{{ URL::to('js/custom.js') }}"></script>




<script src="{{ URL::to('js/pace/pace.min.js') }}"></script>

<!-- skycons -->
<script src="{{ URL::to('js/skycons/skycons.min.js') }}"></script>
<script src="{{ URL::to('js/jquery.datetimepicker.js') }}"></script>
<script src="{{ URL::to('js/local.js') }}"></script>
<style type="text/css">
    .gridular{
        display: none;
    }
    .img-circle {
        border-radius: 50%;
    }
    img {
        vertical-align: middle;
    }
    img {
        border: 0;
    }
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
</style>
<script type="text/javascript">
    function getForPage(url){
        window.location = url;
    }
</script>
@if(session('notice'))
    <script type="text/javascript">
        $.toaster({ priority : "{{ session('notice')['class'] }}", title : "{{ session('notice')['class'] }}", message : "{{ session('notice')['message'] }}"});
    </script>
    <?php session()->forget('notice'); ?>
@endif
