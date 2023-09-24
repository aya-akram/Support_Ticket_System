<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ticket Plus | @yield('title')</title>

    <link rel="stylesheet" href="{{asset('plugin/bootstrap-3.3.7/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/sweetalert/dist/sweetalert.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/datatable/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.min.css')}}">

    <link rel="icon" href="{{asset('images/fav.png')}}" type="image/x-icon"/>

    <!-- Scripts -->
    <script>

    </script>
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand navbar-link" href="{{url('/')}}">
            <img src="{{ asset('uploads/' . $settings->logo) }}" alt="LOGO"></a>
            <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="about"><a href="{{url('/about')}}">About</a></li>
                <li class="contact"><a href="{{url('/contact')}}">Contact</a></li>
                @if(Auth::user())
                    @if(Auth::user()->hasRole('admin'))
                        <li class="ticket"><a href="{{url('tickets')}}">Dashboard</a></li>
                        @else
                        <li class="ticket"><a href="{{url('tickets')}}">Tickets</a></li>
                    @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle clearfix" data-toggle="dropdown">
                        </a>
                        <ul class="dropdown-menu notification_dropdown">
                        {{--   @foreach (Auth::user()->notifications as $notification)
                                @if($notification->type == 'App\Notifications\TicketReply') --}}
                                    <li>
                                        <a href="">
                                            <strong class="badge"></strong>
                                            <small>replied to a ticket</small>
                                            <br><span class="ticket_small_title"></span>
                                        </a>
                                    </li>
                                    {{--   @endif --}}

                                    {{--    @if($notification->type == 'App\Notifications\TicketStatus')--}}
                                        <li>
                                            <a href="">
                                                <small>Ticket status changed to</small>
                                                <strong class="badge"></strong>
                                                <br>
                                                <span class="ticket_small_title"> </span>
                                            </a>
                                        </li>
                                        {{--  @endif --}}

                                            {{--   @if($notification->type == 'App\Notifications\NewTicket')--}}
                                        <li>
                                            <a href="">
                                                <small>New ticket created by</small>
                                                <strong class="badge"></strong>
                                                <br>
                                                <span class="ticket_small_title"> </span>
                                            </a>
                                        </li>
                                {{--    @endif --}}
                                {{--  @endforeach --}}
                        </ul>
                    </li>

                    <li class="dropdown profile">
                        <a href="#" class="dropdown-toggle clearfix" data-toggle="dropdown">
                        {{--   @if(Auth::user()->avatar == null) --}}
                                {{--     @else --}}
                                <span class="avatar"><img src="{{asset('uploads')}}/{{Auth::user()->avatar}}" alt="avatar"></span>

                                {{--   @endif --}}
                            <span class="user_name">{{Auth::user()->name}}</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{url('profile/settings')}}"><i class="fa fa-gear"></i> Profile Settings</a></li>
                            <li><a href="{{url('change/password')}}"><i class="fa fa-lock"></i> Change Password</a></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="fa fa-lock"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>

                @endif

                @if(Auth::guest())
                    <li><a href="{{url('login')}}">Login</a></li>
                    <li><a href="{{url('register')}}" >Register</a></li>
                @endif
                <li><a href="{{url('new/ticket')}}" class="new_ticket">New Ticket</a></li>
            </ul>
        </div>
    </div>
</nav>
@yield('content')


<section id="footer">
    <footer>
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-content">
                        <div class="col-md-12">
                            <div class="section-one">
                            <img src="{{ asset('uploads/' . $settings->footer_logo) }}" alt="LOGO"></a>
                                <p class="media-body">
                                </p>
                                <div class="social-icon">
                                    <ul>
                                        <li><a href="" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="registered">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="one text-center">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</section>

<!--jquery-->
<script src="{{asset('plugin/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugin/bootstrap-3.3.7/js/bootstrap.min.js')}}"></script>
<script src="{{asset('plugin/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{asset('plugin/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugin/slick/slick.min.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>

<script>
    $('.notification_dropdown li a').on('click', function () {
        $.ajax({
            type: 'GET',
            url: '{{url('/markAsRead')}}'
        })
    });
</script>
@yield('script')

</body>
</html>
