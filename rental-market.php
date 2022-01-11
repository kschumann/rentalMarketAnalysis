<?php
function generateActualRentData($propertyId, $count){
	$sql="SELECT propertyId, rentalDate, rentalPrice FROM  rentalHistory 
		WHERE propertyId=" . $propertyId . "
	    ORDER BY rentalDate ASC";
	$result = mysqli_query($GLOBALS['con'],$sql);
	$today = date("Y-m-d");
	$startDate = strtotime($today . '-' . $count . ' weeks');
	$actuals = "";
	$lastDate = $startDate;
	$lastRent = 0;
	$rentHikeOccurred = FALSE;
	while($row = mysqli_fetch_array($result)){
		$rentHikeDate=strtotime($row[1]);
		if($startDate<$rentHikeDate){
			$numWeeks = round(($rentHikeDate-$lastDate)/(60*60*24*7));
			$actuals = $actuals . str_repeat($rent . "," ,$numWeeks);
			$lastDate = strtotime($row[1]);
			$rentHikeOccurred = TRUE;
		}
		$rent=$row[2];
	}

	if($rentHikeOccurred){
		$numWeeks = round((strtotime($today)-$rentHikeDate)/(60*60*24*7));
	} else {
		$numWeeks = $count;
	}
	$actuals = $actuals . str_repeat($rent . ",",$numWeeks);
	$actuals = rtrim($actuals,",");
	return $actuals;
}


function generateMarketJson($propertyId){
	$countSql = "SELECT COUNT(*) FROM aptListings WHERE propertyId=" . $propertyId;
	$countResult = mysqli_query($GLOBALS['con'],$countSql);
	$number = mysqli_fetch_array($countResult);
	$weekCount = $number[0];

	$sql = "SELECT * FROM (SELECT listingDate, avgRent, minRent, maxRent, numUnits FROM aptListings WHERE propertyId=" . $propertyId . " ORDER BY listingDate desc LIMIT 52) AS a ORDER BY listingDate asc";
	$result = mysqli_query($GLOBALS['con'],$sql);

	//echo '"test"';

	$weekOf = "";
	$avgRent = "";
	$minRent = "";
	$maxRent = "";
	$numUnits = "";

	while($row = mysqli_fetch_array($result)){
		$weekOf = $weekOf . "'" . $row[0] . "',";
		$avgRent = $avgRent  .  $row[1] . ",";
		$minRent = $minRent . $row[2] . ",";
		$maxRent = $maxRent . $row[3] . ",";
		$numUnits = $numUnits . $row[4] . ",";
		}

	$weekOf = rtrim($weekOf,",");
	$avgRent = rtrim($avgRent,',');
	$minRent = rtrim($minRent,',');
	$maxRent = rtrim($maxRent,',');
	$numUnits = rtrim($numUnits,',');
	$actualRent = generateActualRentData($propertyId,$weekCount);


	$JSON = " { type:'bar',
			data:{
			labels:[" . $weekOf . "],
			datasets:[{
				label: 'minRent',
				yAxisID:'rent',
				fill:false,
				backgroundColor: '#b2b2b2',
				borderColor: '#b2b2b2',
				data:[" . $minRent . "],
				type:'line'},
				{
				label: 'avgRent',
				yAxisID:'rent',
				fill:false,
				backgroundColor: '#b2b2b2',
				borderColor: '#b2b2b2',
				data:[" . $avgRent . "],
				type:'line'},
				{
				label: 'maxRent',
				yAxisID:'rent',
				fill:false,
				backgroundColor: '#444',
				borderColor: '#444',
				data:[" . $maxRent . "],
				type:'line'},
				{
				label: 'actualRent',
				yAxisID:'rent',
				fill:false,
				backgroundColor: '#ff4949',
				borderColor: '#ff4949',
				data:[" . $actualRent . "],
				type:'line'},
				{
				label: 'numUnits',
				yAxisID:'units',
				fill:false,
				backgroundColor: '#4574f7',
				borderColor: '#4574f7',
				data:[" . $numUnits . "]}]
		},
		  options: {
			responsive: true,
			title: {
				display: true,
				text: 'Apartment Listings'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Week'
					}
				}
				],
				yAxes: [{
					id:'rent',
					type:'linear',
					position:'left',
					scaleLabel: {
						display: true,
						labelString: 'Monthly Rent'
					}
				},
					{
					id:'units',
					type:'linear',
					position:'right',
					ticks:{
					max:30,
					min:0				
					},
					scaleLabel: {
						display: true,
						labelString: 'Number of Units'
					}
				}
	]
				}
			}
		}";	

	return $JSON;
}
?>
