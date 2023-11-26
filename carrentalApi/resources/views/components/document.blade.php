<span class="document">
    <a href="{{url('storage')}}/{{$file->path}}" target="_blank" class="btn btn-sm btn-success mb-1">
        @if ($file->name)
            {{ $file->name }}
        @else
            {{ __('Αρχείο') }} #{{$file->id}}
        @endif
    </a>
    <i class="rm-document fas fa-times"></i>
    <input class="document-input" type="hidden" name="files[]" value="{{ $file->id }}" />
</span>
