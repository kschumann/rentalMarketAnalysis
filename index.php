<?php
include "../resources/master_db_connect.php";
include "rental-market.php";
?>

<!DOCTYPE html>
<html>
<head>
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="../resources/Chart.js"></script>
	<link rel=stylesheet href="styles.css" type="text/css" />
</head>
<body>
<h2 class="section-header">1020 Fairmont</h2>
<div class="description">"Columbia Heights" | 20001 | 2 BR | 1 Bth | 500 - 700 sqft | <a target="_blank" href="https://washingtondc.craigslist.org/search/apa?query=Columbia+heights&search_distance=4&postal=20001&min_bedrooms=2&max_bedrooms=2&min_bathrooms=1&max_bathrooms=1&minSqft=500&maxSqft=700&availabilityMode=0&sale_date=all+dates">craigslist</a></div>
<div class="chart-container" >
	<canvas id="aptListings1" ></canvas>
</div>

<h2 class="section-header">764 Quebec Place - Upstairs</h2>
<div class="description">"Columbia Heights/Petworth" | 20010 | 3 BR | 1 Bth | <a target="_blank" href="https://washingtondc.craigslist.org/search/doc/hhh?query=columbia+heights+petworth&search_distance=4&postal=20010&min_bedrooms=3&max_bedrooms=3&min_bathrooms=1&max_bathrooms=2&availabilityMode=0&sale_date=all+dates">craigslist</a></div>
<div class="chart-container" >
	<canvas id="aptListings2" ></canvas>
</div>

<h2 class="section-header">764 Quebec Pls - Basement</h2>
<div class="description">"Columbia Heights/Petworth" | 20010 | 1 BR | 1 Bth | 450 - 650 sqft | <a target="_blank" href="https://washingtondc.craigslist.org/search/doc/apa?query=columbia+heights+petworth&search_distance=4&postal=20010&min_bedrooms=1&max_bedrooms=1&min_bathrooms=1&max_bathrooms=1&minSqft=450&maxSqft=650&availabilityMode=0&sale_date=all+dates">craigslist</a></div>
<div class="chart-container" >
	<canvas id="aptListings3" ></canvas>
</div>

<h2 class="section-header">605 River Dr</h2>
<div class="description">"Front Royal" | 22630 | 3 BR | 1-2 Bth | - 1600 sqft | <a target="_blank" href="https://winchester.craigslist.org/search/apa?query=Front+royal+va&search_distance=18&postal=22630&min_bedrooms=3&max_bedrooms=3&min_bathrooms=1&max_bathrooms=2&maxSqft=1600&availabilityMode=0&sale_date=all+dates">craigslist</a></div>
<div class="chart-container" >
	<canvas id="aptListings4" ></canvas>
</div>
<script>
window.onload = function() {
var config1 =  <?php echo generateMarketJson(1); ?>;
var config2 =  <?php echo generateMarketJson(2); ?>;
var config3 =  <?php echo generateMarketJson(3); ?>;
var config4 =  <?php echo generateMarketJson(4); ?>;
var ctx1 = document.getElementById('aptListings1').getContext('2d');
var ctx2 = document.getElementById('aptListings2').getContext('2d');
var ctx3 = document.getElementById('aptListings3').getContext('2d');
var ctx4 = document.getElementById('aptListings4').getContext('2d');

window.myLine = new Chart(ctx1, config1);
window.myLine = new Chart(ctx2, config2);
window.myLine = new Chart(ctx3, config3);
window.myLine = new Chart(ctx4, config4);
};

</script>
</body>
</html>
<?php
mysqli_close($con);
?>

