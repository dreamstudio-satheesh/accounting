<!DOCTYPE html>
@php
    use App\Models\Utility;
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = Utility::get_company_logo();
    $company_favicon = Utility::getValByName('company_favicon');
    $seo_setting = App\Models\Utility::getSeoSetting();
    // $logo = Utility::get_superadmin_logo();
    // $settings = \App\Models\Utility::getLayoutsSetting();
    
    $SITE_RTL = env('SITE_RTL');
    $setting = \App\Models\Utility::colorset();
    if ($lang == 'ar' || $lang == 'he') {
        $setting['SITE_RTL'] = 'on';
    }
    $color = 'theme-3';
    if (!empty($setting['color'])) {
        $color = $setting['color'];
    }
    $SITE_RTL = 'theme-3';
    if (!empty($setting['SITE_RTL'])) {
        $SITE_RTL = $setting['SITE_RTL'];
    }
    $mode_setting = \App\Models\Utility::mode_layout();
    $set_cookie = Utility::cookies();
    
@endphp
<html lang="en" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <title>
        {{ Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'Accountgo') }}
        - @yield('page-title')</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />

    <!-- Primary Meta Tags -->
    <meta name="title" content={{ $seo_setting['meta_keywords'] }}>
    <meta name="description" content={{ $seo_setting['meta_description'] }}>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content={{ env('APP_URL') }}>
    <meta property="og:title" content={{ $seo_setting['meta_keywords'] }}>
    <meta property="og:description" content={{ $seo_setting['meta_description'] }}>
    <meta property="og:image" content={{ asset('/' . $seo_setting['meta_image']) }}>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content={{ env('APP_URL') }}>
    <meta property="twitter:title" content={{ $seo_setting['meta_keywords'] }}>
    <meta property="twitter:description" content={{ $seo_setting['meta_description'] }}>
    <meta property="twitter:image" content={{ asset(Storage::url('uploads/metaevent/' . $seo_setting['meta_image'])) }}>

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @if ($SITE_RTL == 'on')
        {{-- <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @endif

</head>

<body class={{ $color }}>
    <div class="auth-wrapper auth-v3">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid pe-2">

                    <a class="navbar-brand" href="#">
                        <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                            alt="{{ config('app.name', 'AccountGo') }}" class="logo logo-lg">
                        {{-- <img src="{{ asset(Storage::url('uploads/logo/' . $logo)) }}" alt="{{ env('APP_NAME') }}"
                    class="logo logo-lg" /> --}}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01" style="flex-grow: 0;">
                        {{--                    @yield('auth-topbar') --}}
                        <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">{{ __('Support') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{ __('Terms') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{ __('Privacy') }}</a>
                            </li>

                            <li class="nav-item ">
                                @yield('auth-lang')
                            </li>
                        </ul>


                    </div>
                </div>
            </nav>
            <div class="card">
                <div class="row align-items-center text-start">
                    <div class="col-xl-6">
                        <div class="card-body">
                            @yield('content')
                        </div>
                    </div>
                    <div class="col-xl-6 img-card-side">
                        <div class="auth-img-content">
                            <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt=""
                                class="img-fluid" />
                            <h3 class="text-white mb-4 mt-5">
                                "Attention is the new currency"
                            </h3>
                            <p class="text-white">
                                The more effortless the writing looks, the more effort the
                                writer actually put into the process.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="auth-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 ">
                            <p class="">
                                {{ Utility::getValByName('footer_text') ? Utility::getValByName('footer_text') : __('Copyright AccountGo') }}
                                {{ date('Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ auth-signup ] end -->

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
    <div class="pct-customizer">
        <div class="pct-c-btn">
            <button class="btn btn-primary" id="pct-toggler">
                <i data-feather="settings"></i>
            </button>
        </div>
        <div class="pct-c-content">
            <div class="pct-header bg-primary">
                <h5 class="mb-0 text-white f-w-500">Theme Customizer</h5>
            </div>
            <div class="pct-body">
                <h6 class="mt-2">
                    <i data-feather="credit-card" class="me-2"></i>Primary color settings
                </h6>
                <hr class="my-2" />
                <div class="theme-color themes-color">
                    <a href="#!" class="" data-value="theme-1"></a>
                    <a href="#!" class="" data-value="theme-2"></a>
                    <a href="#!" class="" data-value="theme-3"></a>
                    <a href="#!" class="" data-value="theme-4"></a>
                </div>

                <h6 class="mt-4">
                    <i data-feather="layout" class="me-2"></i>Sidebar settings
                </h6>
                <hr class="my-2" />
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="cust-theme-bg" checked />
                    <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg">Transparent layout</label>
                </div>

                <h6 class="mt-4">
                    <i data-feather="sun" class="me-2"></i>Layout settings
                </h6>
                <hr class="my-2" />
                <div class="form-check form-switch mt-2">
                    <input type="checkbox" class="form-check-input" id="cust-darklayout" />
                    <label class="form-check-label f-w-600 pl-1" for="cust-darklayout">Dark Layout</label>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
        var pctoggle = document.querySelector("#pct-toggler");
        if (pctoggle) {
            pctoggle.addEventListener("click", function() {
                if (
                    !document.querySelector(".pct-customizer").classList.contains("active")
                ) {
                    document.querySelector(".pct-customizer").classList.add("active");
                } else {
                    document.querySelector(".pct-customizer").classList.remove("active");
                }
            });
        }

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }



        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });

        var custdarklayout = document.querySelector("#cust-darklayout");
        custdarklayout.addEventListener("click", function() {
            if (custdarklayout.checked) {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src", "{{ asset('assets/images/logo.svg') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
            } else {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src", "{{ asset('assets/images/logo-dark.svg') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "{{ asset('assets/css/style.css') }}");
            }
        });

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>
    @stack('custom-scripts')
</body>
@if ($set_cookie['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif

</html>
