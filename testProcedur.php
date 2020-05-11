<html>
<head>
	<script>
	function checkboxclicked(boxid)
	{
			var currentdate = new Date(); 
			var datetime = currentdate.getFullYear()+"-"+(currentdate.getMonth()+1)+"-"+(currentdate.getDate())+" "+currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds();
			document.getElementById("d"+boxid).value=datetime;
	}
		
	</script>
	<style>
		body{
				font-family:Arial Narrow;
		}
		
		td{
				margin:4px;
				padding:4px;
				border-right: 2px solid black;
		}
		
		
		
		table{
				border:2px solid #614875;
				border-collapse: collapse;
		}
		
		th{
				background:#000;
				color:white;
				margin:4px;
				padding:4px;
				border-right: 2px solid black;			
		}
		
		button {
				color:white;
				background:black;
				border:none;
			
				margin:3px;
				padding:3px;
		}
		
		tr:hover {
			background-color: #fecc56;
		}		
		
	</style>
	<script>
	
	</script>
</head>

<body>

<form action='testprocedur.php' method='post'>

<h3>Testprocedur</h3>

<table>
<tr><td colspan='3'>Signatur: <input type='text' name='sign' ></td></tr>
<tr>
		<th>Action</th>
		<th>Finished</th>
		<th>Comment</th>
</tr>
<tr>
		<td>SSH inlogg studentkonto</td>
		<td><input type='checkbox' name='sinl' onchange="checkboxclicked('inl')" ></td>
		<td><input type='text' name='kinl'></td>
		<td><input type='hidden' id='dinl' name='dinl'></td>
</tr>
<tr>
		<td>Uppladdning av html fil med ssh</td>
		<td><input type='checkbox' name='sfileu' onchange="checkboxclicked('fileu')" ></td>
		<td><input type='text' name='kfileu'></td>
		<td><input type='hidden' id='dfileu' name='dfileu'></td>
</tr>
<tr>
		<td>Bekräftad nedladdning html fil med browser</td>
		<td><input type='checkbox' name='sfiled' onchange="checkboxclicked('filed')" ></td>
		<td><input type='text' name='kfiled'></td>
		<td><input type='hidden' id='dfiled' name='dfiled'></td>
</tr>	
<tr>
		<td>Körning av php databastest</td>
		<td><input type='checkbox' name='sphp' onchange="checkboxclicked('php')" ></td>
		<td><input type='text' name='kphp'></td>
		<td><input type='hidden' id='dphp' name='dphp'></td>
</tr>		
</table>
	<button>Save Confirmed Test</button>
</form>

	<?php

		if(isset($_POST['sign'])){
				
			  $log_db = new PDO('sqlite:./testprocedur.db');
				$log_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$log_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$sql = 'CREATE TABLE IF NOT EXISTS testprocedur(id INTEGER PRIMARY KEY,sign text,datum TIMESTAMP,sinl TEXT,dinl TIMESTAMP, kinl TEXT,sfileu TEXT,dfileu TIMESTAMP, kfileu TEXT,sfiled TEXT,dfiled TIMESTAMP, kfiled TEXT,sphp TEXT,dphp TIMESTAMP, kphp TEXT);';
				$log_db->exec($sql);
			
				$sign=$_POST['sign'];
			
				$cols=Array("inl","fileu","filed","php");
				$params=Array();
			
				foreach($cols as $col){
						if(isset($_POST['k'.$col])){
								$params['k'.$col]=$_POST['k'.$col];							
						}else{
								$params['k'.$col]="";
						}
						if(isset($_POST['d'.$col])){
								$params['d'.$col]=$_POST['d'.$col];							
						}else{
								$params['d'.$col]="2020-01-01 00:00";
						}
						if(isset($_POST['s'.$col])){
								$params['s'.$col]=$_POST['s'.$col];							
						}else{
								$params['s'.$col]="off";
						}							 			 
				}
			
				$query = $log_db->prepare('INSERT INTO testprocedur(datum,sign,sinl,dinl,kinl,sfileu,dfileu,kfileu,sfiled,dfiled,kfiled,sphp,dphp,kphp) VALUES (CURRENT_TIMESTAMP,:sign,:sinl,:dinl,:kinl,:sfileu,:dfileu,:kfileu,:sfiled,:dfiled,:kfiled,:sphp,:dphp,:kphp)');
				$query->bindParam(':sign', $sign);
				
				foreach($cols as $col){
					$query->bindParam(':k'.$col, $params['k'.$col]);
					$query->bindParam(':d'.$col, $params['d'.$col]);
					$query->bindParam(':s'.$col, $params['s'.$col]);					
				}

				if(!$query->execute()) {
						$error=$query->errorInfo();
						$debug="Error:\nImporting schedule element from history!\n".$error[2];
				}	

		}

		$log_db = new PDO('sqlite:./testprocedur.db');	
		echo "<table>";
		$cnt=0;
		foreach($log_db->query( 'SELECT * FROM testprocedur;' ) as $row){
			if($cnt==0){
					echo "<tr>";
					foreach($row as $colname => $col){
							if(!is_numeric($colname)){
									echo "<th>".$colname."</th>";
							}
					}
					echo "</tr>";
			}
			echo "<tr>";
			foreach($row as $colname => $col){
					if(!is_numeric($colname)){
							echo "<td>".$col."</td>";
					}
			}
			echo "</tr>";
			$cnt++;
		}
		echo "</table>";		

?>


</body>

</html>