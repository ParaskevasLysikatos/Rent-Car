@extends('layouts.app')

@section('content')
    <script type="text/javascript" src="{{asset('js/webcam.js')}}"></script>
    <script>
        var uploadImage = "{{route('document_upload_image', $lng??'el')}}";
        var booking     = "{{$booking}}";
    </script>

    <!-- -->
    <div class="row text-center">
        <div id="my_camera_col" class="col-sm-7 col-lg-6">
            <div id="my_camera"></div>
            <input id="take_snapshot" type=button>
        </div>
        <div class="col-sm-5 col-lg-6">
            <div id="results"></div>
            <hr>
            <div class="progress" style="height: 20px;">
                <div id="photoshootProgressBar" class="progress-bar bg-success" style="width:0%"></div>
            </div>
            <label>
                <span>{{__('Ανέβηκαν')}}</span>
                <span id="uploadedImages">0</span>/<span id="countedImages">0</span>
            </label>
            <hr>
            <input id="saveImages" type="button" class="btn btn-success" value="{{__('Αποθήκευση')}}">
        </div>
    </div>
@endsection
