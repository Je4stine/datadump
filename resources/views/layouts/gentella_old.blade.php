<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Order Panel - {{ $_SERVER['HTTP_HOST'] }}</title>


  <link href="{{ URL::to('css/bootstrap.min.css') }}" rel="stylesheet">

  <link href="{{ URL::to('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('css/animate.min.css') }}" rel="stylesheet">
  <link href="{{ URL::to('css/custom.css') }}" rel="stylesheet">
  <script src="{{ URL::to('tinymce/tinymce.min.js') }}"></script>

  <script src="{{ URL::to('js/local.js') }}"></script>

  <!-- Custom styling plus plugins -->
  <link href="{{ URL::to('magicsuggest/magicsuggest.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ URL::to('css/maps/jquery-jvectormap-2.0.3.css') }}" />
  <link href="{{ URL::to('css/icheck/flat/green.css') }}" rel="stylesheet" />
  <link href="{{ URL::to('css/floatexamples.css') }}" rel="stylesheet" type="text/css" />

  <script src="{{ URL::to('js/jquery.min.js') }}"></script>
  <script src="{{ URL::to('js/nprogress.js') }}"></script>


  <script src="{{ URL::to('magicsuggest/magicsuggest.js') }}"></script>
  <script src="{{ URL::to('rating/jquery.MetaData.js') }}"></script>
  <link href="{{ URL::to('rating/jquery.rating.css') }}" rel="stylesheet">
  <link href="{{ URL::to('css/range.css') }}" rel="stylesheet">
  <script src="{{ URL::to('rating/jquery.rating.js') }}"></script>
  <link rel="stylesheet" href="{{ URL::to('css/star-rating.css') }}" media="all" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="{{ URL::to('css/chat.css') }}" media="all" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="{{ URL::to('css/throbber.css') }}" media="all" rel="stylesheet" type="text/css"/>
  {{--<script src="{{ URL::to('js/local.js') }}"></script>--}}
  <link href="{{ URL::to('css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">

  <link href="{{ URL::to('intl-tel-input-master/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css" />
  <script src="{{ URL::to('intl-tel-input-master/build/js/intlTelInput.js') }}"></script>

  <script src="{{ URL::to('js/star-rating.js') }}" type="text/javascript"></script>
  <script src="{{ URL::to('js/highcharts/js/highcharts.js') }}" type="text/javascript"></script>
  <script src="{{ URL::to('js/highcharts/js/highcharts-more.js') }}" type="text/javascript"></script>
  <script src="{{ URL::to('js/jquery.toaster.js') }}"></script>
  <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
<link rel="stylesheet" href="{{ URL::to('chosen/chosen.css') }}">
<script src="{{ URL::to('chosen/chosen.jquery.js') }}" type="text/javascript"></script>

</head>


<body class="nav-md">

  <div class="container body">


    <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title" style="border: 0;">
            <a href="{{ URL::to($navbar_menu->url) }}" class="site_title"><i class="fa fa-paw"></i> <span>{{ $navbar_menu->label }}</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img height="" src="@if(@Auth::user()->image) {{ URL::to(Auth::user()->image) }} @else {{ URL::to('images/img.png') }} @endif" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info row">
              <span>Welcome,</span>
              @if(Auth::user())
                <h2>{{ Auth::user()->name }}</h2>
                @else
                <h2>Guest</h2>
              @endif

            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">
                @foreach($menus as $menu)
                  @if($menu->type=='single' && @$menu->allow_writer != 'no')
                    <li>
                      <a href="{{ URL::to($menu->menus->url)  }}"><i class="fa {{ $menu->menus->icon }}"></i> {{ $menu->menus->label }}&nbsp;&nbsp;&nbsp;<span class="" id="{{ @$menu->slug }}"></span></a>
                    </li>
                  @endif

                  @if($menu->type=='many')
                      <li><a><i class="fa {{ $menu->icon }}"></i> {{ $menu->label }} <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu" style="display: none">
                          @foreach($menu->menus as $drop)
                            @if($drop->label)
                            <li><a href="{{ URL::to($drop->url) }}">{{ $drop->label }}</a><li>
                            @endif
                          @endforeach
                        </ul>
                      </li>
                  @endif
                @endforeach
              </ul>
            </div>

          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          {{--<div class="sidebar-footer hidden-small">--}}
            {{--<a data-toggle="tooltip" data-placement="top" title="Settings">--}}
              {{--<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>--}}
            {{--</a>--}}
            {{--<a data-toggle="tooltip" data-placement="top" title="FullScreen">--}}
              {{--<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>--}}
            {{--</a>--}}
            {{--<a data-toggle="tooltip" data-placement="top" title="Lock">--}}
              {{--<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>--}}
            {{--</a>--}}
             {{--<a href="{{ URL::to('logout') }}" data-placement="top" title="Logout">--}}
              {{--<span class="glyphicon glyphicon-off" aria-hidden="true"></span>--}}
            {{--</a>--}}
          {{--</div>--}}
          <!-- /menu footer buttons -->
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">

        <div class="nav_menu">
          <nav class="" role="navigation">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <div class="nav toggle">
              <a href="{{ URL::to('http://'.$_SERVER['HTTP_HOST']) }}" id=""><i class="fa fa-home"></i>Home</a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              @if($navbar_menu->account)
                @foreach($navbar_menu->account as $rmenu)
                  @if(count($rmenu->children)>0 && Auth::user())
                    <?php
                    $label = str_replace("{name}",Auth::user()->name,$rmenu->label);
                    if($rmenu->label=="{email}"){
                      $label =  str_replace("{email}",Auth::user()->email,$rmenu->label);
                    }

                    ?>

                    <li class="">
                      <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="@if(Auth::user()->image) {{ URL::to(Auth::user()->image) }} @else {{ URL::to('images/img.png') }} @endif" alt="">{{ $label }}
                        <span class=" fa fa-angle-down"></span>
                      </a>
                      <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
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
                          </li>
                        @endforeach
                      </ul>
                    </li>
                  @else
                  @endif
                @endforeach
              @endif
              @include('includes.messages')
            </ul>
          </nav>
        </div>

      </div>
      <!-- /top navigation -->



      <div id="right_col" class="right_col" role="main">
        <br/>
        <br/>
        @if(@Auth::user()->role=='admin')
        <form class="form-horizontal">
          <div class="form-group">
            <div class="col-md-4">
              <input name="search" value="{{ Request::get('search') }}" placeholder="Search current records" type="text" class="form-control">
            </div>
          </div>
        </form>
        @endif
        @yield('content')
      </div>
      <!-- /page content -->

    </div>

  </div>

  <div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
  </div>




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


  <!-- dashbord linegraph -->
  <script>



  </script>
  <script>
    NProgress.done();
  </script>
  <!-- /datepicker -->
  <!-- /footer content -->
</body>
<script src="{{ URL::to('js/jquery.datetimepicker.js') }}"></script>
<script type="text/javascript">
  $('a[href="' + window.location.hash + '"]').trigger('click');
  $('input[name="deadline"]').datetimepicker();
</script>
@include('includes.javascript')
</html>
@if(session('notice'))
  <script type="text/javascript">
    $.toaster({ priority : "{{ session('notice')['class'] }}", title : "{{ session('notice')['class'] }}", message : "{{ session('notice')['message'] }}"});
  </script>
  <?php session()->forget('notice'); ?>
@endif
