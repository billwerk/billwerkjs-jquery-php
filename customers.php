<!doctype html>
<html>
	<head>
	    <title>Customers</title>
    	<link rel="stylesheet" href="styles/bootstrap.css" />	    
	</head>

	<body>
		<h3>My customers</h3>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Own Id</th>
					<th>Itero Id</th>
					<th>Name</th>
					<th>Created At</th>
				</tr>
			</thead>
			<tbody>
				
			<?php
			
			include 'config.php';
			
			$m = new MongoClient($GLOBALS['mongodb'],array(
					"connect" => TRUE
					));
			//$m = new MongoClient();
			$db = $m->$GLOBALS['dbname'];
			$c_customers = $db->customers;
			
			$cursor = $c_customers->find();
			
			foreach ($cursor as $doc)
			{
			?>
				<tr>
					<td><?php echo @(string)$doc['_id']; ?></td>
					<td><?php echo @$doc['Id']; ?></td>
					<td><a href="/portal.php?contractid=<?php echo @$doc['contractid']; ?>"><?php echo @$doc['CustomerName']; ?></a></td>
					<td><?php echo @$doc['CreatedAt']; ?></td>
				</tr>					
			<?php
			}
			?>
			</tbody>
		</table>
	</body>
</html>