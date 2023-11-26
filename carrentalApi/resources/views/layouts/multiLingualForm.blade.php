
<div class="card-header">
    @yield('title')
    @yield('card-header')
</div>
@php $rand = rand(); @endphp
<div class="card-body pb-0">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#formData{{ $rand }}"
            data-second-tab="#main-fields-nav{{ $rand }}"
            role="tab" aria-controls="basic_car_info"
            aria-selected="true">{{__('Πληροφορίες')}}</a>
        </li>
        @yield('additional-tabs')
        <li class="nav-item">
            <a class="nav-link" id="langs-tab" data-toggle="tab" href="#formData{{ $rand }}"
            data-second-tab="#multilingual-fields-nav{{ $rand }}"
            role="tab" aria-controls="multilingual-fields"
            aria-selected="false">{{__('Μεταφράσεις')}}</a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="formData{{ $rand }}" class="tab-pane fade show active">
            <form method="POST" action="{{ $formAction }}"
                enctype="multipart/form-data">
                @csrf
                <ul class="hide nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a id="main-fields-nav{{ $rand }}" data-toggle="tab" href="#main-fields{{ $rand }}"
                        role="tab" aria-controls="basic_car_info"
                        aria-selected="true">{{__('Πληροφορίες')}}</a>
                    </li>
                    <li class="nav-item">
                        <a id="multilingual-fields-nav{{ $rand }}" data-toggle="tab" href="#multilingual-fields{{ $rand }}"
                        role="tab" aria-controls="multilingual-fields"
                        aria-selected="false">{{__('Μεταφράσεις')}}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="main-fields{{ $rand }}" class="tab-pane fade show active">
                        @yield('main-fields')
                    </div>
                    <div id="multilingual-fields{{ $rand }}" class="tab-pane fade">
                        @yield('multilingual-fields')
                    </div>
                </div>
                <div class="card-footer multilingual-card-footer">
                    <a href="{{ $formCancel }}"
                    class="btn btn-warning btn-cancel text-left">{{__('Ακύρωση')}}</a>
                    <button type="submit" class="btn btn-success float-right">{{ $formSubmit }}</button>
                </div>
            </form>
        </div>
        @yield('additional-tabs-content')
    </div>
</div>
