<div class="row">
    <div class="col-6">
        {{ $pagination->links() }}
    </div>
    <div class="col-6">
        <form action="{{route('pagination')}}" method="POST" id="change_pages" name="change_pages">
            @csrf
            <select name="pages" id="pages" class="form-control">
                @for($i=5;$i<=200;$i = $i * 2)
                    @if(Cookie::get('pages') == $i)
                        <option value="{{$i}}" selected>{{$i}} {{__('αποτελέσματα')}}</option>
                    @else
                        <option value="{{$i}}">{{$i}} {{__('αποτελέσματα')}}</option>
                    @endif
                @endfor
            </select>
        </form>
    </div>
</div>

