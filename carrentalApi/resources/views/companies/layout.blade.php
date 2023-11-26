@section('additional-tabs')
    {{-- <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#cmpdriverstab">{{__('Οδηγοί')}}</a>
    </li> --}}
@endsection

@section('main-fields')
    @include('companies.form')
@endsection

{{--
@section('additional-tabs-content')
<div class="tab-pane container" id="cmpdriverstab">
    <div class="company_drivers" id="company_drivers">
        <div class="d-flex">
            <div class="p-2 flex-fill">
                <div class="input-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ _('Οδηγοί') }}:</span>
                    </div>
                    <div class="input-group-prepend flex-grow-1">
                        @driverSelector([
                            'name' => 'drivers',
                            'drivers' => isset($company) ? $company->drivers : [],
                            'multiple' => true
                        ])
                        @enddriverSelector
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
