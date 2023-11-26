<div class="col-sm-3 text-center mb-4 image_{{$file->id}}">
    <img class="img-thumbnail"
        src='{{ asset('storage/'.$file->path) }}'
        width="150">
    <br/>
    @if(Auth::user()->role->id != "service")
        <input class="btn btn-sm btn-warning image_link"
            data-image_link_id="{{ $image_link_id }}"
            data-image_link_type="{{ $image_link_type }}"
            data-image_id="{{$file->id}}" type="button"
            value="{{__('Διαγραφή')}}"/>
    @endif
</div>
