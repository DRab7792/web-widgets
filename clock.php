<?php
date_default_timezone_set('America/New_York');
function writeContent(){
	echo "<div class='time'><h1 class='hidden' id='timer'>".date('g:iA',time())."</h1></div><div class='clock hidden'>CLOCK</div>";
	echo "<div class='date hidden'>".date('l F, jS Y',time())."</div>";
}
?>
<html>
	<head>
		<title>Header</title>
		<style type="text/css">
			.time{
				display:block;
				border-right:2px solid black;
				position: relative;
				top:0px;
				width:45%;
				height:80px;
				padding-right: 5%;
				overflow-y:hidden;
				text-align:right;
			}
			#timer{
				font-family:"Melbourne";
				font-size:72px;
				font-style: normal;
				overflow:hidden;
				display: block;
				float:right;
				width:0px;
				margin-top: 0px;
			}
			.clock{
				width:3px;
				display:block;
				position: relative;
				padding-left: 5%;
				top:-80px;
				left:50%;
				font-size:72px;
				margin-right:0px;
				font-family: "UpperEastSide";
				border-left:2px solid black;
			}
			.hidden{
				color: white;
			}
			.date{
				border-top: 2px solid black;
				margin-left:auto;
				margin-right:auto;
				width:80%;
				text-align:center;
				display:block;
				position:relative;
				font-family:"Melbourne";
				font-size:36px;
				top:-80px;
				height:3px;
			}
			.container{
				margin-left:auto;
				margin-right:auto;
				display: block;
				width:41%;
			}
		</style>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
		<script>
			$(document).ready(function(){ setInterval(clock, 60000); setTimeout(animation,<?php echo $_GET['delay']?>); });
			function animation(){
				$(".date").removeClass("hidden").animate({height: "40px"}, <?php echo $_GET['speed']?>);
				$(".clock").removeClass("hidden").animate({width: "55%"}, <?php echo $_GET['speed']?>);
				$("#timer").removeClass("hidden").animate({width: "100%"}, <?php echo $_GET['speed']?>);
			}
			function clock() {
      			now = new Date();
      			var hour24 = now.getHours();
      			var meridian = "AM";
      			var hour12 = hour24;
      			if (hour24>12){
      				hour12 = hour24-12;
      				meridian = "PM";
      			}else if (hour24==0){
      				hour12 = 12;
      			}
      			var minutes = now.getMinutes();
      			if(minutes<10) {var min="0"+minutes;}
      			else {var min = minutes;}
      			$("#timer").text(hour12+":"+min+meridian);
			}

		</script>
	</head>
	<body>
		<div class="container">
			<?php 
				writeContent();
			?>
		</div>
	</body>
</html>
