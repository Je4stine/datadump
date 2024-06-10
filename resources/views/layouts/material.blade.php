@if(Request::ajax())
    <div class="container">

                        @if(@$_GET['success'])
                            <div class="alert alert-success">{{ $_GET['success'] }}</div>
                        @endif
                        @if(@$_GET['error'])
                            <div class="alert alert-danger">{{ $_GET['error'] }}</div>
                        @endif
                        @yield('content')

    </div>
    @else
@include('common.material_header')
<header id="header" class="clearfix" data-ma-theme="blue">
    <ul class="h-inner">
        <li class="hi-trigger ma-trigger" data-ma-action="sidebar-open" data-ma-target="#sidebar">
            <div class="line-wrap">
                <div class="line top"></div>
                <div class="line center"></div>
                <div class="line bottom"></div>
            </div>
        </li>

        <li class="hi-logo hidden-xs">
            <a href="{{ URL::to('') }}">Order Panel</a>
        </li>
        <li class="pull-right">
            <ul class="hi-menu">
                <li class="dropdown">
                    <a data-toggle="dropdown" href="">
                        <i class="him-icon zmdi zmdi-notifications"></i>
                        <i class="him-counts " id="message_count">--</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg pull-right">
                        <div class="list-group him-notification">
                            <div class="lg-header">
                                <a href="{{ url("departments/messages") }}">Inbox</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
    <div class="h-search-wrap">
        <div class="hsw-inner">
            <i class="hsw-close zmdi zmdi-arrow-left" data-ma-action="search-close"></i>
            <input type="text">
        </div>
    </div>
</header>
<section id="main">
    <aside id="sidebar" class="sidebar c-overflow">
        <div class="s-profile">
            <a href="" data-ma-action="profile-menu-toggle">
                <div class="sp-pic">
                    <img src="{{ URL::to('img/profile-pics/2.jpg') }}" alt="">
                </div>

                <div class="sp-info">
                    @if(Auth::user())
                   {{ Auth::user()->email }}
                    @else
                        Please Login
                    @endif

                    <i class="zmdi zmdi-caret-down"></i>
                </div>
            </a>

            <ul class="main-menu profile-info">
                @if(Auth::user())
                <li>
                    <a class="load-page" href="{{ URL::to("user/profile") }}"><i class="zmdi zmdi-account"></i> View Profile</a>
                </li>
                <li>
                    <a href="{{ url('logout') }}"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                </li>
                    @endif
            </ul>
        </div>
        <ul class="main-menu">
            @foreach($menus as $menu)
                @if($menu->type=='single' && @$menu->menus)
                    <li>
                        <a class="load-page" href="{{ URL::to($menu->menus->url)  }}"><i class="zmdi {{ $menu->menus->icon }}"></i> {{ $menu->menus->label }}&nbsp;&nbsp;&nbsp;<span class="{{ @$menu->slug }}"></span></a>
                    </li>
                @endif

                @if($menu->type=='many')
                        <li class="sub-menu">
                            <a href="" data-ma-action="submenu-toggle"><i class="zmdi {{ $menu->icon }}"></i> {{ $menu->label }}</a>
                            <ul>
                                @foreach($menu->menus as $drop)
                                    @if($drop->label)
                                        <li><a class="load-page" href="{{ URL::to($drop->url) }}">{{ $drop->label }}</a><li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                @endif
            @endforeach
        </ul>
    </aside>

    <section id="content" class="system-container">


                                @if(@$_GET['success'])
                                    <div class="alert alert-success">{{ $_GET['success'] }}</div>
                                @endif
                                @if(@$_GET['error'])
                                    <div class="alert alert-danger">{{ $_GET['error'] }}</div>
                                @endif
                            @yield('content')

    </section>
</section>
<input type="hidden" name="material_page_loaded" value="1">;
@include('includes.messages')


@include('common.material_footer')
@endif

