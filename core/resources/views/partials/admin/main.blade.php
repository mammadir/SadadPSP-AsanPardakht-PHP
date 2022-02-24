<main class="py-4">
    <div class="container-fluid">
        @if(auth()->check())
            <div class="row justify-content-center">
                @include('fp::partials.admin.sidebar')
                @include('fp::partials.admin.content')
            </div>
        @else
            @yield('content')
        @endif
    </div>
    
    <div class="container-fluid">
        <div class="mb-4"></div>
        <p class="text-center">توسعه توسط <a href="https://faradadeh.com" target="_blank">فراداده</a></p>
        </div>
    
    
</main>