<?

use Illuminate\Support\Facades\Route;

$routes = Route::getRoutes();
$routes_arr = [];
foreach ($routes as $route) {
	$name = $route->getName();
	$uri = $route->uri();
	$methods = $route->methods();

	if ($name == '') continue;
	if (strpos($name, 'debugbar') > -1) continue;
	//if (strpos($name, 'api') > -1) continue;
	if (strpos($name, 'ignition') > -1) continue;
	if (strpos($name, 'sanctum') > -1) continue;

	//echo $route->getName() . ' | ' .  $methods[0] . '<br>';
	$routes_arr[] = [
		'name' => $name,
		'uri' => '/' . $uri,
		'method' => $methods[0]
	];
}
sort($routes_arr);
?>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div style="width: 1000px; margin: auto; ">
	User: {{\Illuminate\Support\Facades\Auth::user()->name ?? 'none'}} ({{count($routes_arr)}})
	<br><br>
<form style="display: inline-block;">

	<select id="route" style="width: 200px;">
		@foreach ($routes_arr as $route)
			<option value="{{$route['uri']}}~{{$route['method']}}">{{$route['name']}}</option>
		@endforeach
	</select>

	<button	onclick="send(); return false;">отправить</button>
	<br><br>

	<textarea style="width: 300px; height: 100px;" id="vals"></textarea>

</form>
	<span id="rezult_block" style="vertical-align: top; padding: 0 30px; display: inline-block"></span>
</div>
<script>
	function send() {
		var route = document.getElementById('route').value;
		var url = route.split('~')[0];
		var method = route.split('~')[1];
		var data = document.getElementById('vals').value;
		fetch(url, {
			method: method,
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json', // Указываем формат отправляемых данных
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, // Добавляем CSRF-токен, если требуется
			},
			body: method=='GET' ? null : data,
		})
			.then(response => response.json())
			.then(data => {
				var rezult = JSON.stringify(data, null, 2);
				const rezult_block = document.getElementById('rezult_block');
				console.log(rezult)
				//rezult_block.textContent = rezult;
			})
			.catch(error => console.error('Ошибка:', error));
	}
</script>
