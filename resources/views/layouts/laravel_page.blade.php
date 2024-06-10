<!-- <link href="{{ URL::to('css/bootstrap.min.css') }}" rel="stylesheet"> -->
<link href="{{ URL::to('css/ianbootstrap.css') }}" rel="stylesheet">
<link href="{{ URL::to('css/font-awesome.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('js/jquery.min.js') }}"></script>
  <link rel="stylesheet" href="{{ URL::to('css/star-rating.css') }}" media="all" rel="stylesheet" type="text/css"/>
<link href="{{ URL::to('intl-tel-input-master/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ URL::to('intl-tel-input-master/build/js/intlTelInput.js') }}"></script> 
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script> -->
  <!-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
  <script src="{{ URL::to('js/jquery.toaster.js') }}"></script>
  <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('js/local.js') }}"></script>
  <link rel="stylesheet" href="{{ URL::to('css/chat.css') }}" media="all" rel="stylesheet" type="text/css"/>
  <script src="{{ URL::to('js/star-rating.js') }}" type="text/javascript"></script>
  <script src="{{ URL::to('rating/jquery.MetaData.js') }}"></script>
  <link href="{{ URL::to('rating/jquery.rating.css') }}" rel="stylesheet">
  <script src="{{ URL::to('rating/jquery.rating.js') }}"></script>
  <script src="{{ URL::to('js/star-rating.js') }}" type="text/javascript"></script>
    <link href="{{ URL::to('intl-tel-input-master/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css" />
  <script src="{{ URL::to('intl-tel-input-master/build/js/intlTelInput.js') }}"></script>
  <script src="{{ URL::to('chosen/chosen.jquery.js') }}"></script>
<script src="{{ URL::to('js/highcharts/js/highcharts.js') }}" type="text/javascript"></script>
<script src="{{ URL::to('js/highcharts/js/highcharts-more.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="{{ URL::to('chosen/chosen.css') }}">
<div style="margin:7px;" class="bootstrap-iso">
@include('includes.javascript')
   <h3>Order Panel</h3>
<ul class="nav nav-tabs">
    <?php
    $no=0;
    $url = Request::url();
    $math_no = 0;
    ?>
    @foreach($menus as $menu)
        @if($menu->type=='single' && @$menu->allow_writer != 'no')
            <?php
            $no++;
            if($url==URL::to($menu->menus->url)){
                $math_no = "tab_".$menu->slug;
            }
            ?>
            <li onclick="getForPage('{{ URL::to($menu->menus->url) }}');" class="{{ $url==URL::to($menu->menus->url) ? "active":"" }}"><a data-toggle="tab" href="{{ URL::to($menu->menus->url) }}">{{ $menu->menus->label }}&nbsp;&nbsp;<span id="{{ $menu->slug }}"></span></a></li>
        @endif
    @endforeach
    @if(Auth::user())
        @include('includes.messages')
    @endif
    @if(@$navbar_menu->account)
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
</ul>

<div class="tab-content">
    <div id="{{ $math_no }}" class="tab-pane fade in active">
@include('includes.javascript')
        @yield('content')

    </div>

</div>
</div>



<script type="text/javascript">
    function getForPage(url){
        window.location = url;
    }
</script>
<style type="text/css">
    .bootstrap-iso .container {
    width: 1170px;
    width: 100% !important;
}
</style>
@if(session('notice'))
    <script type="text/javascript">
        $.toaster({ priority : "{{ session('notice')['class'] }}", title : "{{ session('notice')['class'] }}", message : "{{ session('notice')['message'] }}"});
    </script>
    <?php session()->forget('notice'); ?>
@endif