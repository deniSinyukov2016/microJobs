<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Testers') }}</title>
    <meta name="generator" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content=""/>
    <link href="/css/app.css" rel="stylesheet">
    @stack('styles')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">

</head>
<body>

<div class="page-container" id="app">
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".sidebar-nav">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{url('/')}}">
                    <img src="/img/logo.png" class="logo" style="height: 25px;"/>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                <i class="fa fa-dashboard"></i> Jobs Manager<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @permission('read-jobs-manager')
                                <li>
                                    <a href="{{ route('jobs-admin') }}"><i class="fa fa-dashboard"></i>Jobs manager</a>
                                </li>
                                @endpermission

                                @permission('read-job-categories')
                                <li><a href="{{ route('categories.index') }}"><i class="fa fa-th-list"></i>
                                        Categories</a></li>
                                @endpermission

                                @permission('read-job-skills')
                                <li>
                                    <a href="{{ route('skills.index') }}"><i class="fa fa-th-list"></i> Skills</a>
                                </li>
                                @endpermission

                                @permission('read-job-applications')
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('applications-admin') }}"><i class="fa fa-briefcase"></i>
                                        Job Applications</a></li>
                                <li role="separator" class="divider"></li>
                                @endpermission

                                @permission('create-jobs')
                                <li><a href="/jobs/create"><i class="fa fa-plus-circle"></i> Post new job </a></li>
                                @endpermission


                            </ul>
                        </li>

                        @permission('read-payouts')
                        <li><a href="/payouts"><i class="fa fa-money"></i> Payouts</a></li>
                        @endpermission

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                <i class="fa fa-user-circle"></i> Account<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/account"><i class="fa fa-user"></i> My Account</a></li>
                                <li>
                                    <a href="{{ route('my-bookmarks') }}"><i class="fa fa-bookmark"></i> Bookmarks
                                        <span class="badge pull-right">{{count(Auth::user()->bookmarks)}}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('my-applications')}}"><i class="fa fa-briefcase"></i> Applications
                                        <span class="badge">{{count(Auth::user()->applications->where('status','!=','complete'))}}</span>
                                    </a>
                                </li>
                                <li role="separator" class="divider"></li>
                                @permission('read-users')
                                <li><a href="{{ route('users') }}"><i class="fa fa-group"></i> Users</a></li>
                                @endpermission
                                <li role="separator" class="divider"></li>

                                @role('admin')
                                <li><a href="/admin/settings"><i class="fa fa-wrench"></i> Settings</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/roles"><i class="fa fa-key"></i> Roles</a></li>
                                @endrole

                                @permission('read-logs')
                                <li><a href="/admin/logs"><i class="fa fa-history"></i> System logs</a></li>
                                <li><a href="/admin/debug"><i class="fa fa-bug"></i> Debug logs</a></li>
                                @endpermission
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fa fa-lock"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row row-offcanvas row-offcanvas-left">

            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
                <ul class="nav">
                    <li class="active"><a href="/">Home</a></li>
                    @foreach(\App\Models\Jobs\Categories::orderBy('cat_order','ASC')->get() as $cat)
                        <li>
                            <a href="/jobs/category/{{$cat->id}}">{{ucwords($cat->name)}}
                                <span class="label label-info pull-right">{{count($cat->openJobs)}}</span>
                            </a>
                            {{--//TODO this code was added --}}
                            @if(count($cat->subcategories) > 0)
                                <ul>
                                    @foreach($cat->subcategories as $subcategory)
                                        <li>
                                            <a href="/jobs/category/{{$subcategory->id}}">{{ucwords($subcategory->name)}}
                                                <span class="label label-info pull-right">{{count($subcategory->openJobs)}}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

            </div>

            <div class="col-xs-12 col-sm-9" id="main">
                <div style="margin-top:10px;"></div>
                @yield('content')
            </div>
        </div>
    </div>
</div>

<button id="back2Top" class="btn btn-default btn-sm" title="Back to top"><i class="fa fa-chevron-up"></i></button>

<script src="/js/app.js" type="text/javascript"></script>
<script src="/plugins/listjs/listjs.min.js" type="text/javascript"></script>
@if(Auth::check())
    <script src="/js/main.js" type="text/javascript"></script>
@else
    <script type="text/javascript">
        $('document').ready(function () {
            $('.share-job-btn,.bookmark-job,.apply-job-btn,.box .job-desc, .box .panel-heading h4')
                .click(function () {
                    return notice('Please login to enable feature! <a href="/login">click to login</a>', 'error');
                });
        });
    </script>
@endif

@role('admin')
<script src="/js/admin.js"></script>
@endrole

@if(config('app.env')=='production')
    <script>

        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        ga('create', '', '');
        ga('send', 'pageview');
    </script>
@endif


@include('partials.flash')

@stack('scripts')
@stack('modals')

</body>
</html>