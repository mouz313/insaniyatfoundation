@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )

@if (config('adminlte.usermenu_profile_url', false))
    @php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" style="gap:6px;">
        @if(config('adminlte.usermenu_image'))
            <img src="{{ Auth::user()->adminlte_image() }}"
                 class="user-image img-circle elevation-2"
                 style="width:28px;height:28px;object-fit:cover;border:2px solid rgba(255,255,255,0.3);"
                 alt="{{ Auth::user()->name }}">
        @endif
        <span class="d-none d-md-inline font-weight-bold" style="font-size:14px;">
            {{ Auth::user()->name }}
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">

        {{-- User menu header --}}
        <li class="user-header bg-gradient-danger text-center py-4">
            <img src="{{ Auth::user()->adminlte_image() }}"
                 class="img-circle elevation-2"
                 style="width:80px;height:80px;object-fit:cover;border:3px solid rgba(255,255,255,0.3);"
                 alt="{{ Auth::user()->name }}">
            <p class="mt-2 mb-0 font-weight-bold" style="font-size:16px;">
                {{ Auth::user()->name }}
                <small class="d-block text-white-50" style="font-size:13px;font-weight:400;margin-top:2px;">
                    @foreach(Auth::user()->getRoleNames() as $role)
                        {{ ucfirst($role) }}@if(!$loop->last), @endif
                    @endforeach
                </small>
            </p>
        </li>

        {{-- Configured user menu links --}}
        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

        {{-- User menu body --}}
        @hasSection('usermenu_body')
            <li class="user-body border-bottom">
                @yield('usermenu_body')
            </li>
        @endif

        {{-- User menu footer --}}
        <li class="user-footer d-flex px-3 py-3" style="gap:8px;">
            @if($profile_url)
                <a href="{{ $profile_url }}" class="btn btn-danger btn-sm flex-fill rounded-pill">
                    <i class="fas fa-user-circle mr-1"></i> Profile
                </a>
            @endif
            <a class="btn btn-outline-secondary btn-sm flex-fill rounded-pill"
               href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>

    </ul>
</li>
