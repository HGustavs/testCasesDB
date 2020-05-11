<html>
<head>
	<style>
		body{
				font-family:Arial Narrow;
		}
		
		td{
				margin:4px;
				padding:4px;
		}
		
		table{
				border:2px solid #614875;
				border-collapse: collapse;
		}
		
		th{
				background:#000;
				color:white;
		}
		
	</style>
</head>

<body style='font-family:Arial Narrow;'>

<?php
 		echo "<h3>Connecting</h3>";
	
		// Attempt connection
		$connected=false;
		try{
    		$pdo = new PDO('mysql:host=localhost', 'myusername', 'mypassword');
				$connected=true;
		}catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
	
		if($connected){
			
 				echo "<h3>Successfully Connected</h3>";			
			
				// Testing connection
			
				try{
						$pdo->query( "DROP DATABASE IF EXISTS adminTest;");
				}catch(PDOException $e){
						echo "Failed to drop database adminTest: " . $e->getMessage();
				}
			
				try{
						$pdo->query( "CREATE DATABASE adminTest;");
				}catch(PDOException $e){
						echo "Failed to create database adminTest: " . $e->getMessage();
				}

				try{
						$pdo->query( "USE adminTest;");
				}catch(PDOException $e){
						echo "Failed to connect to adminTest: " . $e->getMessage();
				}
			
				try{
						$pdo->query( "CREATE TABLE admTest(teststring VARCHAR(40),skapelsetid TIMESTAMP);");
				}catch(PDOException $e){
						echo "Failed to create database adminTest: " . $e->getMessage();
				}
	
				echo "Connection / Table Initiation Test\n";		
				$cnt=0;
				echo "<table><tr><th>Table Name</th></tr>";
				foreach($pdo->query( 'show tables;' ) as $row){
					echo "<tr>";
					echo "<td>".$row[0]."</td>";
					echo "</tr>";
					$cnt++;
				}
				echo "</table>";
			
				if($cnt!=1){
						echo "Table count not equal to one, table creation unsuccessful.";
				}
			
 				echo "<h3>Testing INSERT / SELECT of data</h3>";		
			
				// Testing data 
				try{
						$pdo->query( "INSERT INTO admTest(teststring,skapelsetid) VALUES('Testing',NOW());");
				}catch(PDOException $e){
						echo "Failed to create item in table adminTest: " . $e->getMessage();
				}		
			
				$cnt=0;
				echo "<table>";			
				try{
						foreach($pdo->query( 'SELECT * FROM admTest;' ) as $row){
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
				}catch(PDOException $e){
						echo "Failed to read data from admTest database: " . $e->getMessage();
				}
			
 				echo "<h3>Testing user account creation</h3>";
			
				try{
						$pdo->query( "CREATE USER IF NOT EXISTS 'malteknapp'@'localhost' IDENTIFIED BY 'stpd';");
				}catch(PDOException $e){
						echo "Failed to create user in database: " . $e->getMessage();
				}		
			
				$cnt=0;
				echo "<table>";			
				try{
						foreach($pdo->query( 'SELECT * FROM mysql.user;' ) as $row){
							if($cnt==0){
									echo "<tr>";
									foreach($row as $colname => $col){
											if(!is_numeric($colname)){
													echo "<th>".$colname."</th>";
											}
									}
									echo "</tr>";
							}
							if($row['User']=='malteknapp'){
									echo "<tr style='background:#614875;color:white;' >";							
							}else{
									echo "<tr style='background:#fecc56;color:#614875;' >";							
							}

							foreach($row as $colname => $col){
									if(!is_numeric($colname)){
											echo "<td>".$col."</td>";
									}
							}
							echo "</tr>";
							$cnt++;
						}
						echo "</table>";
				}catch(PDOException $e){
						echo "Failed to retrieve user accounts: " . $e->getMessage();
				}

		}

 		echo "<h3>Testing assignment of privileges</h3>";
	
		try{		
			$pdo->exec( "GRANT ALL PRIVILEGES ON * TO 'malteknapp'@'localhost';");
		}catch(PDOException $e){
				echo "Failed to retrieve user accounts: " . $e->getMessage();
		}
			
	    
		echo "<table>";	
		$cnt=0;
		try{
				foreach($pdo->query( "SHOW GRANTS FOR 'malteknapp'@'localhost';" ) as $row){
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
		}catch(PDOException $e){
				echo "Failed to retrieve user accounts: " . $e->getMessage();
		}
	
?>
</body>
</html>
 