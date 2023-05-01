<html>
	<head>
		<?php header("Access-Control-Allow-Origin: *"); ?>
		<script src="scripts/jquery.min.js"></script>
		<script type="text/javascript">
			function ultrasonicRefresh() {
				$("#ultrasonic").load("ultrasonic.php");
			}
			setInterval(ultrasonicRefresh, 500);
			function forward() {
				$.ajax({
					url: "forward.php",
					type: "POST",
					success: function(data) {}
				});
			}
			function backward() {
				$.ajax({
					url: "backward.php",
					type: "POST",
					success: function(data) {}
				});
			}
			function left() {
				$.ajax({
					url: "left.php",
					type: "POST",
					success: function(data) {}
				});
			}
			function right() {
				$.ajax({
					url: "right.php",
					type: "POST",
					success: function(data) {}
				});
			}
			function stop() {
				$.ajax({
					url: "stop.php",
					type: "POST",
					success: function(data) {}
				});
			}
		</script>
		<style>
		html, body {
			padding: 20px;
			font-family: 'Arial';
			overflow: hidden;
		}
		h1 {
			width: 100%;
			text-align: center;
		}
		h3 {
			width: 100%;
			text-align: center;
			padding-bottom: 25px;
			font-weight: 300;
		}
		#container {
			display: flex;
			gap: 30px;
		}
		#camera img {
			height: 75%;
			margin-bottom: 25px;
			border: 1px solid black;
		}
		h2, #ultrasonic {
			font-size: 1.5em;
			font-weight: bold;
		}
		#buttons {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-around;
			height: calc(75% + 10px);
			aspect-ratio: 1/1;
		}
		.cell {
			flex: 0 0 calc(33.3% - 20px);
			margin-bottom: 20px;
			background-color: #eeeeee;
			border: 1px solid black;
		}
		button {
			width: 100%;
			aspect-ratio: 1/1;
			font-size: 1.3em;
			border: none;
			background-color: #dddddd;
		}
		button:hover, button:focus {
			background-color: #bbbbbb;
		}
		</style>
	</head>
	<body>
		<h1>Web Controlled Robot Car</h1>
		<h3>Prakhar Mittal, Meghna Jain, Nicolas Rios, Minseung Jung</h3>
		<div id="container">
			<div id="camera">
				<h2>Front-facing Camera:</h2>
				<img style="-webkit-user-select: none;" src="http://<?php echo $_SERVER['SERVER_ADDR']; ?>:8081/" onerror="this.onerror=null; this.src='http://172.20.10.11:8081/'">
				<div id="ultrasonic"></div>
			</div>
			<div id="control">
				<h2>Drive Control:</h2>
				<form method="post" id="buttons">
					<div class="cell"></div>
					<div class="cell">
						<button type="button" onclick="forward()">Forward</button>
					</div>
					<div class="cell"></div>
					<div class="cell">
						<button type="button" onclick="left()">Left</button>
					</div>
					<div class="cell">
						<button type="button" onclick="stop()">Stop</button>
					</div>
					<div class="cell">
						<button type="button" onclick="right()">Right</button>
					</div>
					<div class="cell"></div>
					<div class="cell">
						<button type="button" onclick="backward()">Backward</button>
					</div>
					<div class="cell"></div>
				</form>
			</div>
		</div>
	</body>
</html>

