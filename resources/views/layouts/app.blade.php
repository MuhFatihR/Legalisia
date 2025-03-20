<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title> @yield('title', 'Home') | {{ config('app.name') }}</title>
    @include('inc.metatag')
    @include('inc.styles')
</head>

<body>
  @include('inc.theme-loader')
  <div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
      <div class="pcoded-container navbar-wrapper">
        @include('inc.navbar')
        <div class="pcoded-main-container">
          <div class="pcoded-wrapper">
            @include('inc.sidebar')
            <div class="pcoded-content">
              <div class="pcoded-inner-content">
                <div class="main-body">
                  @include('inc.messages')
                  <div class="page-wrapper">
                    @yield('content')
                  </div>
                  <div class="md-overlay"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  @include('inc.scripts')
</body>
</html>