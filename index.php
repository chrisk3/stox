<!doctype html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Stox</title>

	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/stox_layout.css">

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="assets/js/d3.v3.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a href="#" class="navbar-brand"><strong>STOX</strong></a>
			</div>
		</div>
	</div>

	<div class="container">
		<form action="process.php" method="post" id="stocks">
			<div class="form-group">
				<label for="company_name1">First Company Symbol: </label>
				<input type="text" name="company_name1" id="company_name1" class="form-control">
			</div>
			<div class="form-group">
				<label for="company_name2">Second Company Symbol: </label>
				<input type="text" name="company_name2" id="company_name2" class="form-control">
			</div>
			<input type="submit" value="Get Data" class="btn btn-primary">
		</form>
	</div>

	<div class="container">
		<script type="text/javascript">

			var w = 400;
			var h = 400;
			var padding = 30;

			var svg = d3.select("body").append("svg").attr("width", w).attr("height", h).attr("class", "center");

			$(document).on('submit', '#stocks', function() {
				$.post(
					$(this).attr('action'),
					$(this).serialize(),
					function(dataset) {
						var max = 0;
						var close1 = dataset[0].close;
						var close2 = dataset[1].close;
						if (parseInt(close1) > parseInt(close2)) {
							max = close1;
						} else {
							max = close2;
						}

						var scale = d3.scale.linear().domain([0, max]).rangeRound([40, 120]);
						var color = d3.scale.linear().domain([0, max]).rangeRound([0, 255]);

						svg.selectAll("circle")
							.data(dataset)
							.enter()
							.append("circle")
							.attr("cx", function(d, i) {
								if (i == 0) {
									return (w * 0.65);
								} else {
									return (w * 0.35);
								}
							})
							.attr("cy", function(d, i) {
								if (i == 0) {
									return (h * 0.45);
								} else {
									return (h * 0.55);
								}
							})
							.attr("r", function(d) {
								return scale(d.close);
							})
							.attr("fill", function(d) {
								return "rgb(0, " + color(d.close) + ", 0)"
							})
							.attr("stroke", "gray")
							.attr("stroke-width", "1")
							.attr("opacity", "0.60");

						svg.selectAll("text")
							.data(dataset)
							.enter()
							.append("text")
							.text(function(d) {
								return d.name + " ($" + d.close + ")";
							})
							.attr("x", function(d, i) {
								if (i == 0) {
									return (w * 0.65);
								} else {
									return (w * 0.35);
								}
							})
							.attr("y", function(d, i) {
								if (i == 0) {
									return (w * 0.45);
								} else {
									return (w * 0.55);
								}
							})
							.attr("font-family", "sans-serif")
							.attr("font-size", "12px")
							.attr("font-weight", "bold")
							.attr("fill", "white")
							.attr("text-anchor", "middle");
						},
					"json");
				return false;
			});

	    </script>
	</div>

</body>
</html>
