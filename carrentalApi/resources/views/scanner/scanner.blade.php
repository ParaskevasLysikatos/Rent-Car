@extends('layouts.app')

@section('content')
    <script>
        var current_language = "{{ $lng ?? 'el' }}";
    </script>

    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="text-center">
                <h4>{{__('Αναζήτηση αυτοκινήτου με QR Code')}}</h4>
                <hr/>
            </div>

        </div>
        <div class="row justify-content-center text-center">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="well" style="position: relative;display: inline-block;">
                            <canvas width="320" height="240" id="webcodecam-canvas"></canvas>
                            <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                        </div>
                        <img width="320" height="240" id="scanned-img" src="" class="hidden" hidden>
                        <p id="scanned-QR" class="hidden" hidden></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <select class="form-control" id="camera-select" style="max-width: 300px;margin: 0 auto;margin-bottom: 10px;"></select>
                    </div>
                </div>
                <div class="row d-none">
                    <div class="col-sm-12">
                        <div class="navbar-form navbar-right">
                            <div class="form-group">
                                <button title="Decode Image" class="btn btn-default btn-sm" id="decode-img" type="button" data-toggle="tooltip" hidden>
                                    <span class="fas fa-id-card"></span>
                                </button>

                                <button title="Image shoot" class="btn btn-info btn-sm disabled" id="grab-img" type="button" data-toggle="tooltip" hidden>
                                    <span class="fas fa-image"></span>
                                </button>

                                <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip">
                                    <span class="fas fa-play"></span>
                                </button>

                                <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip">
                                    <span class="fas fa-pause"></span>
                                </button>
                                <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip">
                                    <span class="fas fa-stop"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-sm-12 scanner-options">
                        <button type="button" class="collapsible text-center fas fa-cogs"> {{__('Ρυθμίσεις')}}</button>
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <br/>
                                    <label id="zoom-value" >Zoom: 1</label>
                                    <input class="form-control" id="zoom" onchange="Page.changeZoom();" type="range" min="10" max="30" value="20">
                                </div>

                                <div class="col-sm-12">
                                    <label id="brightness-value">Brightness: 0</label>
                                    <input class="form-control" id="brightness" onchange="Page.changeBrightness();" type="range" min="0" max="128" value="0">
                                </div>
                                <div class="col-sm-12">
                                    <label id="contrast-value" >Contrast: 0</label>
                                    <input class="form-control" id="contrast" onchange="Page.changeContrast();" type="range" min="0" max="64" value="0">
                                </div>
                                <div class="col-sm-12">
                                    <label id="threshold-value" >Threshold: 0</label>
                                    <input class="form-control" id="threshold" onchange="Page.changeThreshold();" type="range" min="0" max="512" value="0">
                                </div>
                                <div class="col-sm-6">
                                    <label id="sharpness-value" >Sharpness: off</label>
                                    <input id="sharpness" onchange="Page.changeSharpness();" type="checkbox">
                                </div>
                                <div class="col-sm-6">
                                    <label id="grayscale-value">grayscale: off</label>
                                    <input id="grayscale" onchange="Page.changeGrayscale();" type="checkbox">
                                </div>
                                <div class="col-sm-6">
                                    <label id="flipVertical-value">Flip Vertical: off</label>
                                    <input id="flipVertical" onchange="Page.changeVertical();" type="checkbox">
                                </div>
                                <div class="col-sm-6">
                                    <label id="flipHorizontal-value">Flip Horizontal: off</label>
                                    <input id="flipHorizontal" onchange="Page.changeHorizontal();" type="checkbox">
                                </div>
                            </div>
                        </div>
                    </div>

                </div> --}}
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('js/filereader.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/qrcodelib.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/webcodecamjs.js')}}"></script>
    {{-- <script type="text/javascript"> var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent"; var arg = { resultFunction: function(result) { var aChild = document.createElement('li'); aChild[txt] = result.format + ': ' + result.code; document.querySelector('body').appendChild(aChild); } }; var decoder = new WebCodeCamJS("canvas").init(arg).buildSelectMenu('select', 1); setTimeout(function(){ decoder.play(); }, 500); </script> --}}
    <script type="text/javascript" src="{{asset('js/main.js')}}"></script>
@endsection
