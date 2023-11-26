<?php $lng = Lang::locale(); ?>
<!doctype html>
<html lang="{{ str_replace('_', '-', $lng ?? 'el') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(View::hasSection('title'))
            @yield('title') |
        @endif
        {{ config('app.name', 'Laravel') }}</title>


    <script src="{{ asset(mix('js/app.js')) }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.45/css/bootstrap-datetimepicker-standalone.min.css"> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> --}}

    <link href="https://cdn.jsdelivr.net/gh/mobius1/selectr@latest/dist/selectr.min.css" rel="stylesheet" type="text/css">

	<script src="https://rentalbook.eu/eml/public/js/selectr-edit.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.dirtyforms/2.0.0/jquery.dirtyforms.min.js" integrity="sha512-W6DphLVq1HUtS0+F8cjcrVJWxZDzgPNgvGAdk9e9JMGynNnXEVWvNSVTFT2paAcoq0ZRrblp3KUixkERkKKncA==" crossorigin="anonymous"></script>
    @if (Auth::id())
        <script>
            var datepickerFormat = "{{ config('ea.datepicker_date_format') }}";
            var datetimepickerFormat = "{{ config('ea.datepicker_datetime_format') }}";
            var momentFormat = "{{ config('ea.moment_date_format') }}";
            var momentDatetimeFormat = "{{ config('ea.moment_datetime_format') }}";
            var defaultFormat = 'YYYY-MM-DD';
            var defaultTimeFormat = 'HH:mm:ss';
            var defaultDateTimeFormat = defaultFormat + ' ' + defaultTimeFormat;
            var extra_time = "{{ config('preferences.checkin_free_minutes') }}";
            var decimalSeparator = "{{ config('ea.number_decimal_separator') }}";
            var thousandsSeparator = "{{ config('ea.number_thousands_separator') }}";
            var decimalSeparatorReg = (decimalSeparator == '.' ? '\\'+decimalSeparator : decimalSeparator);
            var thousandsSeparatorReg = (thousandsSeparator == '.' ? '\\'+thousandsSeparator : thousandsSeparator);
            var removeImageLink = "{{ route('remove_image_link', $lng ?? 'el') }}";
            var findPlaces = "{{ route('populate_places', $lng ?? 'el') }}";

            var typingTimer = 700;                //timer identifier
            var doneTypingInterval = 700;  //time in ms, 5 second for example
            var searchUserUrl = "<?php echo e(route('search_user_ajax', $lng)); ?>";
            var searchCompanyUrl = "<?php echo e(route('search_company_ajax', $lng)); ?>";
            var searchAgentUrl = "<?php echo e(route('search_agent_ajax', $lng)); ?>";
            var searchDriverUrl = "<?php echo e(route('search_driver_ajax', $lng)); ?>";
            var searchDriverAndContactUrl = "<?php echo e(route('search_driver_with_contacts_ajax', $lng)); ?>";
            var searchGroupUrl = "<?php echo e(route('search_group_ajax', $lng)); ?>";
            var searchVehicleUrl = "<?php echo e(route('search_vehicle_ajax', $lng)); ?>";
            var searchSourceUrl = "<?php echo e(route('search_source_ajax', $lng)); ?>";
            var searchStationUrl = "<?php echo e(route('search_station_ajax', $lng)); ?>";
            var searchPlaceUrl = "<?php echo e(route('search_place_ajax', $lng)); ?>";
            var searchExtrasUrl = "<?php echo e(route('search_option_ajax', ['locale' => $lng, 'option_type' => 'extras'])); ?>";
            var searchInsurancesUrl = "<?php echo e(route('search_option_ajax', ['locale' => $lng, 'option_type' => 'insurances'])); ?>";
            var searchContactUrl = "<?php echo e(route('search_contact_ajax', $lng)); ?>";
            var searchRentalUrl = "<?php echo e(route('search_rental_ajax', $lng)); ?>";
            var searchPaymentUrl = "<?php echo e(route('search_payment_ajax', ['locale' => $lng, 'payment_type' => \App\Payment::PAYMENT_TYPE])); ?>";
            var editModalUrl = "{{ route('ajax_edit_modal', $lng ?? 'el') }}";
            var addModalUrl = "{{ route('ajax_add_modal', $lng ?? 'el') }}";
            var searchTransactorUrl = "{{ route('search_transactor_ajax', $lng ?? 'el') }}";
            var searchTransactorFromRentalUrl = "{{ route('search_rental_transactor_ajax', $lng ?? 'el') }}";
            var searchSubAccountUrl = "{{ route('search_sub_account_ajax', $lng ?? 'el') }}";
            var searchSubAccountWithAgentUrl = "{{ route('search_sub_account_with_agent_ajax', $lng ?? 'el') }}";
            var searchTagUrl = "{{ route('search_tag_ajax', $lng ?? 'el') }}";
            var singlePrinterUrl = "{{ route('get_single_printer', $lng ?? 'el') }}"

            var pricesWithVat = false;
        </script>
    @endif
</head>

<body>
