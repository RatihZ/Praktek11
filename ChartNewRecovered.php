<?php

include('koneksi.php');
$kasus = mysqli_query($koneksi, "SELECT * FROM tb_covid");
while ($row = mysqli_fetch_array($kasus)) {
	$negara[] = $row['Country'];

	$query = mysqli_query($koneksi, "SELECT sum(New_Recovered) as New_Recovered FROM tb_covid where id='". $row['id']."'");
	$row = $query->fetch_array();
	$sembuh_baru[] = $row['New_Recovered'];

}

?>

<!DOCTYPE html>
<html>
<head>
	<title> Bar Chart - Sembuh Baru </title>
	<script type="text/javascript" src="Chart.js"></script>
</head>

<body>

	<div style="width: 700px; height: 700px">
		<canvas id="myChart"></canvas>
	</div>

	<script>
		var ctx = document.getElementById("myChart").getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: <?php echo json_encode($negara); ?>, datasets: [{
					label: 'Grafik Sembuh Baru Covid-19',
					data: <?php echo json_encode($sembuh_baru); ?>,
					backgroundColor: 'rgba(246, 67, 67, 0.8)',	
		  		    borderColor: 'rgba(144, 132, 132, 0.8',
					borderWidth: 1
				}]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				}
			}
		});
	</script>

	 <div style="margin: -700px 800px;width: 700px;height: 700px">
    <canvas id="lineChart"></canvas>
  </div>


  <script>
    var ctx = document.getElementById("lineChart").getContext('2d');
    var lineChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($negara); ?>,
        datasets: [{
          label: 'Grafik Sembuh Baru Covid-19',
          data: <?php echo json_encode($sembuh_baru); ?>,
          backgroundColor: 'rgba(246, 67, 67, 0.8)',	
		  borderColor: 'rgba(144, 132, 132, 0.8',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      }
    });
</script>

	  <div id="canvas-holder" style="margin:  500px 390px;width:50%">
		<canvas id="chart-area"></canvas>
	</div>
	<script>
		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data:<?php echo json_encode($sembuh_baru); ?>,
					backgroundColor: [
					'rgb(0, 0, 205)',
					'rgb(252, 165, 3)',
					'rgb(178, 34, 33)',
					'rgb(34, 139, 35)',
					'rgb(253, 215, 3)',
					'rgb(135, 206, 250)',
					'rgb(128, 0, 128)',
					'rgb(64, 224, 208)',
					'rgb(127, 255, 1)',
					'rgb(255, 0, 0)'
					],
					label: 'Presentase Sembuh Baru Covid-19'
				}],
				labels: <?php echo json_encode($negara); ?>},
			options: {
				responsive: true
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('chart-area').getContext('2d');
			window.myPie = new Chart(ctx, config);
		};

		document.getElementById('randomizeData').addEventListener('click', function() {
			config.data.datasets.forEach(function(dataset) {
				dataset.data = dataset.data.map(function() {
					return randomScalingFactor();
				});
			});

			window.myPie.update();
		});

		var colorNames = Object.keys(window.chartColors);
		document.getElementById('addDataset').addEventListener('click', function() {
			var newDataset = {
				backgroundColor: [],
				data: [],
				label: 'New dataset ' + config.data.datasets.length,
			};

			for (var index = 0; index < config.data.labels.length; ++index) {
				newDataset.data.push(randomScalingFactor());

				var colorName = colorNames[index % colorNames.length];
				var newColor = window.chartColors[colorName];
				newDataset.backgroundColor.push(newColor);
			}

			config.data.datasets.push(newDataset);
			window.myPie.update();
		});

		document.getElementById('removeDataset').addEventListener('click', function() {
			config.data.datasets.splice(0, 1);
			window.myPie.update();
		});
	</script>
</body>
</html>