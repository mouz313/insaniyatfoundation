@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@php
    $ngoLogo = \App\Models\Setting::where('key', 'ngo_logo')->value('value');
    $preloaderImg = $ngoLogo ? 'storage/' . $ngoLogo : config('adminlte.preloader.img.path', 'vendor/adminlte/dist/img/AdminLTELogo.png');
@endphp

<div class="{{ $preloaderHelper->makePreloaderClasses() }}" style="{{ $preloaderHelper->makePreloaderStyle() }}">

    @hasSection('preloader')

        @yield('preloader')

    @else

        <img src="{{ asset($preloaderImg) }}"
             class="img-circle {{ config('adminlte.preloader.img.effect', 'animation__shake') }}"
             alt="{{ config('adminlte.preloader.img.alt', 'AdminLTE Preloader Image') }}"
             width="{{ config('adminlte.preloader.img.width', 60) }}"
             height="{{ config('adminlte.preloader.img.height', 60) }}"
             style="animation-iteration-count:infinite; object-fit: cover;">

    @endif

</div>
