@include ('template-parts/head')
@include ('template-parts/header')
@include ('template-parts/sidebar')
<div class="page-wrapper">
    <div class="page-content">
        @include ('template-parts/page')
        @yield ('content')
    </div>
</div>
@include('template-parts/modals')
@include ('template-parts/footer')
@stack('modals')
@stack('scripts')
<script>
    @if (isset($new) && $new)
        $(document).ready(function () {
            $('#print_file').trigger('click');
            $('#print_files').trigger('click');
            $('#print_files2').trigger('click');
            const inputs = $('#printModalFiles').find('input');
            for (const input of inputs) {
                $(input).prop("checked", true);
            }
            $('#print-selected-files').trigger('click');
        });
    @endif
    @php
        if (!isset($view)) {
            $view = !isset($view) && isset($_REQUEST['view']) ? $_REQUEST['view'] : false;
        }
    @endphp
    @if (isset($view) && $view == true)
        $(document).ready(function () {
            $('[type="submit"]').addClass('d-none');
            $(':input:not(.preview_fee):not(.close):not(.btn-default)').prop('disabled', true);
            const selects = $('select.ajax-selector');
            for (const select of selects) {
                select.selectr.disable();
            }
        });
    @endif
</script>
