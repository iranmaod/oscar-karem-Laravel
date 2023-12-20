@php
$user = auth()->user();
@endphp

<div class="navbar-bg hs-ok-navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar hs-ok-main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav hs-ok-navbar-nav mr-3">
            <li><a href="#" data-turbolinks="false" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
        <h1 class="font-weight-bold text-2xl text-white">{{ config('app.name', 'Laravel') }}</h1>
    </form>
    <ul class="navbar-nav navbar-right hs-ok-navbar-nav hs-ok-navbar-right">
        <li class="dropdown"><a href="#" data-turbolinks="false" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            @if (!is_null($user))
                <div class="d-sm-none d-lg-inline-block hs-ok-username">{{__('Hi,')}} {{ $user->name }}</div></a>
            @else
                <div class="d-sm-none d-lg-inline-block hs-ok-username">{{__('Hi, Welcome')}}</div></a>
            @endif
            <div class="dropdown-menu dropdown-menu-right hs-ok-profile-dropdown">
                <a href="{{url('/user/profile')}}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> {{__("Profile")}}
                </a>



                @if(session()->get('hs_language', 'en') == 'en')
                <a href="{{route('language.set', ['code' => 'de'])}}" class="dropdown-item has-icon">
                    <i class="fa fa-language"></i> Switch to German
                </a>
                @else
                <a href="{{route('language.set', ['code' => 'en'])}}" class="dropdown-item has-icon">
                    <i class="fa fa-language"></i> Switch to English
                </a>
                @endif
                @if (request()->get('is_admin'))
                <a href="{{url('/setting')}}" class="dropdown-item has-icon ">
                    <i class="fas fa-cog"></i> {{__('Setting')}}
                </a>
                @endif
                <div class="dropdown-divider hs-ok-dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault();this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt"></i> {{__("Logout")}}
                    </a>
                </form>
            </div>
        </li>
    </ul>
</nav>
