<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>График запросов по минутам</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

@if(isset($print_arr))
@foreach($print_arr as $stroke)
	{{$stroke}}<br>
@endforeach
@endif


<h2>Количество запросов по минутам</h2>
<canvas id="myChart"></canvas>

<script>
	const labels = {!! json_encode($labels) !!};
	const values = {!! json_encode($values) !!};

	new Chart(document.getElementById("myChart"), {
		type: 'line',
		data: {
			labels: labels,
			datasets: [{
				label: 'Количество запросов',
				data: values,
				borderColor: 'blue',
				fill: false
			}]
		},
		options: {
			scales: {
				x: {
					title: { display: true, text: 'Время (минуты)' }
				},
				y: {
					title: { display: true, text: 'Число запросов' },
					beginAtZero: true
				}
			}
		}
	});
</script>

</body>
</html>
