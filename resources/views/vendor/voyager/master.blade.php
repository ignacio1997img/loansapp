<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="assets-path" content="{{ route('voyager.voyager_assets') }}"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style/dataTable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style/h.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/style/loading.css') }}">

    {{-- show swetalert message --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif



    <!-- App CSS -->
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">

    @yield('css')
    @if(__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif

    <!-- Few Dynamic Styles -->
    <style type="text/css">
        .voyager .side-menu .navbar-header {
            background:{{ config('voyager.primary_color','#22A7F0') }};
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary{
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus{
            background:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .voyager .breadcrumb a{
            color:{{ config('voyager.primary_color','#22A7F0') }};
        }
    </style>

    @if(!empty(config('voyager.additional_css')))<!-- Additional CSS -->
        @foreach(config('voyager.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
    @endif

    @yield('head')
</head>
    <body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">


        <div id="voyager-loader">
            <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
            @if($admin_loader_img == '')
                <img src="{{ asset('images/loading.png') }}" alt="Voyager Loader">
            @else
                <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
            @endif
        </div>

        <?php
            if (\Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'http://') || \Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'https://')) {
                $user_avatar = Auth::user()->avatar;
                // dd($user_avatar);
            } else {
                $user_avatar = Voyager::image(Auth::user()->avatar);
                // dd($user_avatar);
            }
        ?>

        <div class="app-container">
            <div class="fadetoblack visible-xs"></div>
            <div class="row content-container">
                @include('voyager::dashboard.navbar')
                @include('voyager::dashboard.sidebar')
                <script>
                    (function(){
                            var appContainer = document.querySelector('.app-container'),
                                sidebar = appContainer.querySelector('.side-menu'),
                                navbar = appContainer.querySelector('nav.navbar.navbar-top'),
                                loader = document.getElementById('voyager-loader'),
                                hamburgerMenu = document.querySelector('.hamburger'),
                                sidebarTransition = sidebar.style.transition,
                                navbarTransition = navbar.style.transition,
                                containerTransition = appContainer.style.transition;

                            sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                            appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                            navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                            if (window.innerWidth > 768 && window.localStorage && window.localStorage['voyager.stickySidebar'] == 'true') {
                                appContainer.className += ' expanded no-animation';
                                loader.style.left = (sidebar.clientWidth/2)+'px';
                                hamburgerMenu.className += ' is-active no-animation';
                            }

                        navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                        sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                        appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
                    })();
                </script>
                <!-- Main Content -->
                <div class="container-fluid">
                    <div class="side-body padding-top">
                        @yield('page_header')
                        <div id="voyager-notifications"></div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        @include('voyager::partials.app-footer')

        <!-- Javascript Libs -->


        <script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>


        <script>
            //para que cada ves que cambie de pagina verfifique si hay prestamos con dias atrazados
            $(function() {             
                $.get('{{route('loans-loanDay.late')}}', function (data) {
                            // alert(2);
                });
                //     }, 8000 //10000 en medio minuto se recargará solo la campana de notificación..
                // );

                $.get('{{route('garments-month.late')}}', function (data) {
                            // alert(2);
                });

                // toastr.success('Detalle agregado', 'Exito')
                // toastr.info('<a href="{{url('admin#rowCashierOpen')}}" style="font-size: 15px; color:black">Tiene una Caja Pendiente Asignada</a>', 'Notificación',
                //     {   "positionClass" : "toast-bottom-right",
                //         "href" : "admins"
                //     }
                // );

            
            });




            

        </script>




        <script>
            @if(Session::has('alerts'))
                let alerts = {!! json_encode(Session::get('alerts')) !!};
                helpers.displayAlerts(alerts, toastr);
            @endif

            @if(Session::has('message'))

            // TODO: change Controllers to use AlertsMessages trait... then remove this
            var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
            var alertMessage = {!! json_encode(Session::get('message')) !!};
            var alerter = toastr[alertType];

            if (alerter) {
                alerter(alertMessage);
            } else {
                toastr.error("toastr alert-type " + alertType + " is unknown");
            }
            @endif
        </script>

        <script src="{{ asset('vendor/momentjs/moment.min.js') }}"></script>
        <script src="{{ asset('vendor/momentjs/moment-with-locales.min.js') }}"></script>

        {{-- <script src="{{ asset('js/timbre.js') }}"></script> --}}



        <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.1/howler.min.js"></script>
            <script>       
                $(function() {
                    // $.get('{{route('getAuth')}}', function (data) {    
                    //     alert(data)      
                    // })
                    notification();
                });

                function notification()
                {
                    let count = 0;
                    $.get('{{route('notification.cashierOpen')}}', function (data) {    
                        count = 1;
                        
                        if(data)
                        {
                            var luz = '<span class="badge badge-danger navbar-badge" id="bandeja">'+count+'</span>';
                            $('#countNotification').html(luz)        
                            $('#notificationInbox').append(`
                                <a href="{{url('admin#rowCashierOpen')}}" style="font-size: 16px; color:black"><i class="fa-solid fa-cash-register"></i><small> Tiene una Caja Pendiente Asignada</small></a>
                                <hr>
                            `);

                            // setTimeout(function(){
                            //     alert('dos');
                            // }, 1000)
                            setInterval(() => {
                            
                                    $('#bellNotification').html('<i class="voyager-bell" style="font-size: 1.8em; color : #ff0808"></i>');
                        
                                setTimeout(function(){
                                    $('#bellNotification').html('<i class="fa-solid fa-bell" style="font-size: 1.8em; color : #22a7f0"></i>')  ;
                                }, 500)
                                

                            }, 1000);
                        }
                        else
                        {
                            $('#countNotification').html('')
                            $('#notificationInbox').html('')
                        }       
                    })
                }

                // function getAuth()
                // {
                //     $.get('{{route('getAuth')}}', function (data) {    
                //         return data;
                //     })
                // }
            </script>


            <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.0/socket.io.js" integrity="sha512-nYuHvSAhY5lFZ4ixSViOwsEKFvlxHMU2NHts1ILuJgOS6ptUmAGt/0i5czIgMOahKZ6JN84YFDA+mCdky7dD8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                const socket = io("{{ env('SOCKET_URL').':'.env('SOCKET_PORT') }}");

            
                socket.on(`change notificationCashierOpen`, data => {
                    // let auth =  @json(App\Models\User::where('id',  1)->first());
                    
                    let auth =  @json(Auth::user());


                    if(auth.id == data.auth.id)
                    {
                        notification()
                        toastr.info('<a href="{{url('admin#rowCashierOpen')}}" style="font-size: 15px; color:black">Hola '+data.auth.name+', Tiene una Caja Pendiente Asignada</a>', 'Notificación',
                            {   "positionClass" : "toast-bottom-right",
                                "timeOut": "10000",
                                "closeButton": true,
                                "progressBar": true,
                            }
                        );
                    }
                    
                });

                // $(function() {

                //     toastr.info('<a style="font-size: 15px; color:black">Hola, Tiene una Caja Pendiente Asignada</a>', 'Notificación',
                //             {   "positionClass" : "toast-bottom-right",
                //                 "timeOut": "5000",
                //                 "closeButton": true,
                //                 "progressBar": true,
                //             }
                //         );

                // });

            
            </script>

            {{-- ########################################################################### --}}

        @include('voyager::media.manager')

        @yield('javascript')
        @stack('javascript')
        @if(!empty(config('voyager.additional_js')))<!-- Additional Javascript -->
            @foreach(config('voyager.additional_js') as $js)<script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach
        @endif

        {{-- Loading --}}
        <script src="{{ asset('vendor/loading/loading.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('vendor/loading/loading.css') }}">

        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
                $('.form-submit').submit(function(){
                    $('.form-submit .btn-submit').attr('disabled', 'disabled');
                });
            });
        </script>

    </body>
</html>
