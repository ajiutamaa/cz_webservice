<?php
require("config.php");

if(!empty($_POST)){

//title lattitude longtitude waktu dilaporkan list of crime typenya

//Select all cities that are closer than 50km (31 miles)
//sumber rumus: http://stackoverflow.com/questions/7758728/linq-to-sql-query-trouble
  $q_checkReport = "SELECT Report_Detail.reportID, title, x_coordinate, y_coordinate, time, CategoryName 
                    FROM (

                    SELECT Crime_Report.reportID, title, data_created, x_coordinate, y_coordinate, time
                    FROM (

                    SELECT reportID, x_coordinate, y_coordinate, ( 3959 * ACOS( COS( RADIANS( :latitude ) ) * COS( RADIANS( x_coordinate ) ) * COS( RADIANS( y_coordinate ) - RADIANS( :longitude ) ) + SIN( RADIANS( :latitude ) ) * SIN( RADIANS( x_coordinate ) ) ) ) AS distance
                    FROM Crime_Location
                    HAVING distance <:target_distance /1000
                    ORDER BY distance ASC
                    ) AS reportAndDistance
                    LEFT OUTER JOIN Crime_Report ON Crime_Report.reportID = reportAndDistance.reportID
                    ) AS Report_Detail
                    LEFT OUTER JOIN Crime_Categories ON Crime_Categories.reportID = Report_Detail.reportID";
                    
//distance = "x km" , loc_lattitude = "nilai double", loc_longtitude = "nilai double"
  $query_params = array(
      ':target_distance' => $_POST['distance'],
			':latitude' => $_POST['latitude'],
      ':longitude' => $_POST['longitude'],
	);

  try{
		$stmt = $db->prepare($q_checkReport);
		$result = $stmt->execute($query_params);
	} catch(PDOException $e){
		$response["success"] = 0;
		$response["message"] = "Database Error." . $e->getMessage();
		die(json_encode($response));
	}
  $row = $stmt->fetch();

  if(!$row){
    $response["success"] = 0;
    $response["message"] = "No location found.";
    die(json_encode($response));
  }
  else {
  $category= $row['CategoryName'];
  $cekID = $row['reportID'];
  //print json_encode($cekID);
  $ctg = array();
  $ctg[] = $category;
  $row['CategoryName'] = $ctg;
  $tmprow = $row;

  //source: http://stackoverflow.com/questions/21170992/mysql-query-to-pull-rows-and-print-them-as-json
  while($row = $stmt->fetch())
  {
  $category= $row['CategoryName'];
  if($row['reportID']!=$cekID){
    $crimeReport[] = $tmprow;
    //print json_encode($tmprow);
    //print json_encode($cekID);
    $cekID = $row['reportID'];
    $ctg = array();
    $ctg[] = $category;
    $row['CategoryName'] = $ctg;
    $tmprow = $row;
  }
  else {
    //print json_encode($cekID);
    $ctg[] = $category;
    $tmprow['CategoryName'] = $ctg;
  }
  }

  $crimeReport[] = $tmprow;
  
  print json_encode($crimeReport);		
 }
	}


	else{

echo ($_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);//
//echo $_REQUEST['json'];

	?>

	<h1>Test GetLocation</h1>
	<form action="crimeLocation.php" method="post">
		<table>
			<tr>
				<td>x_coordinate</td>
				<td><input type="text" name="x_coordinate" value=""></td>
			</tr>
			<tr>
				<td>y_coordinate</td>
				<td><input type="text" name="y_coordinate" value=""></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="submitLocation"></td>
			</tr>
		</table>
	</form>
	<?php

	}
?>
