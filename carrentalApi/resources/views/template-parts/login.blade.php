<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/superuser.js') }}" defer></script>
    <script src="{{ asset('js/doctor.js') }}" defer></script>
    <script src="{{ asset('js/default.js') }}" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="login-page">
    <header>
        <div class="img">
            <img height="50px" src="{{ URL::to('/') }}/images/logo-sima-whiteVersion.svg" alt="Logo" class="light-logo">
            <img height="50px" src="{{ URL::to('/') }}/images/logo-lektiko-whiteVersion.svg" class="light-logo" alt="Text logo">
        </div>
    </header>
    <main>
        <div class="bg-image"></div>
        <div class="container-fluid login-content">
            @yield('content')
            <div class="custom">
                <div>
                <p style="text-align: center;">Η on-line υπηρεσία επιστημονικής υποστήριξης "Ophthalmica CLUB" απευθύνεται αποκλειστικά και μόνο σε συνεργάτες οφθαλμίατρους που έχουν παραπέμψει περιστατικά τους στις εγκαταστάσεις μας.</p>
                <p style="text-align: center;">Για εγγραφή στο "Ophthalmica CLUB" επικοινωνήστε μαζί μας:<br><b>Τηλ.: 2310 263063, 2310 413131</b><br><b>E-mail: info@ophthalmica.gr</b><br><br>Θα σας αποσταλλεί member card με συγκεκριμένα permissions (username &amp; password) για ασφαλή διαδικασία login.</p>
                <p style="text-align: center;"><span class="contentpane">* Το σύστημα διαχείρισης του "Ophthalmica CLUB" προστατεύεται από ειδικές τεχνολογίες ασφάλειας περιήγησης και πλοήγησης δεδομένων που εγγυώνται το ιατρικό απόρρητο.</span></p>
                </div>
            </div>
        </div>
    </main>
    <footer>
        © 2020 <a href="https://www.ophthalmica.gr/el/" target="_blank">Οφθαλμολογικό Κέντρο Ophthalmica</a> -
        designed by <a href="http://www.e-avenue.eu/" target="_blank">e-avenue</a>
    </footer>
</body>
