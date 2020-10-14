<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('shopify-app.app_name') }}</title>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://unpkg.com/turbolinks"></script>
        @yield('styles')
    </head>

    <body>
        <div class="app-wrapper">
            <div class="app-content">
                <main role="main">
                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <a class="navbar-brand" href="{{ url('/') }}">Handwrytten</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                        </button>
                      
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                          <ul class="navbar-nav mr-auto">
                            <li class="nav-item @if(Request::path() == '/') active @endif">
                            <a class="nav-link" href="{{ url('/') }}">Dashboarda <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item @if(Request::path() == '/') active @endif">
                            <a class="nav-link" href="{{ url('/shopifyOrders') }}">Orders <span class="sr-only">(current)</span></a>
                            </li>
                            {{-- <li class="nav-item @if(Request::path() == 'webhook/orders/create') active @endif">
                                <a class="nav-link" href="{{ url('webhook/orders/create') }}">Webhook</a>
                            </li> --}}
                            {{-- <li class="nav-item @if(Request::path() == 'customers') active @endif">
                              <a class="nav-link" href="{{ url('customers') }}">Customers</a>
                            </li>
                            <li class="nav-item @if(Request::path() == 'products') active @endif">
                                <a class="nav-link" href="{{ url('products') }}">Products</a>
                              </li>
                              <li class="nav-item @if(Request::path() == 'settings') active @endif">
                                <a class="nav-link" href="{{ url('settings') }}">Settings</a>
                              </li> --}}
                    
                          </ul>
                        </div>
                      </nav>
                    @yield('content')
                </main>
            </div>
        </div>

        @if(config('shopify-app.appbridge_enabled'))
            <script src="https://unpkg.com/@shopify/app-bridge{{ config('shopify-app.appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
            <script>
                var AppBridge = window['app-bridge'];
                var createApp = AppBridge.default;
                var app = createApp({
                    apiKey: '{{ config('shopify-app.api_key') }}',
                    shopOrigin: '{{ Auth::user()->name }}',
                    forceRedirect: true,
                });
            </script>

            @include('shopify-app::partials.flash_messages')
            
        @endif

        @yield('scripts')
    </body>
</html>