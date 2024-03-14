<b>Новый заказ</b>

@foreach($data as $key => $value)
<b>{{ucfirst($key)}}</b>: {{$value}}
@endforeach