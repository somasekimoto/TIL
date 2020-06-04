test success<br>

<!-- '{}'を二重にすることで、XSS攻撃を防ぐため、自動でphpのhtmlspecialchars関数を通される -->
@foreach($values as $value)
  
  {{$value->id}}<br>
  {{$value->text}}<br>
@endforeach