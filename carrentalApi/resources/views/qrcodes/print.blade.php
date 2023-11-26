<style>
    .qrcodes {
        text-align: center;
    }
    .qrcode {
        display: inline-block;
        border: 2px solid #378e5e;
        margin: 10px;
    }
    .plate {
        padding: 10px 1px;
        border-top: 2px solid #378e5e;
    }
</style>
<div class="qrcodes">
    @foreach($qrcodes as $qrcode)
    <div class="qrcode">
        <div class="code">{!! $qrcode['img'] !!}</div>
       <div class="plate">{{ $qrcode['plate'] }}</div>
    </div>
    @endforeach
</div>
