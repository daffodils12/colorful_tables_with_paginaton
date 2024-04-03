 
<?php
// Below is optional, remove if you have already connected to your database.
$mysqli = mysqli_connect("localhost",'film','test123', 'address_book');

// Number of results to show on each page.
$num_results_on_page = 4;

//Max nr of pages to display
$max_pages_to_display=10;

$prev_set=1;
$next_set=11;
$clicked_page=1;

// Get the total number of records from our table "worldcup_standings".
$total_pages = round($mysqli->query('SELECT * FROM worldcup_standings')->num_rows/$num_results_on_page);
$max_tot_pages = $total_pages;

// Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$clicked_page=$page;

if ($stmt = $mysqli->prepare('SELECT * FROM worldcup_standings ORDER BY id LIMIT ?,?')) {
	// Calculate the page to get the results we need from our table.
	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	// Get the results...
	$result = $stmt->get_result();
	}
	?>
 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
<TITLE>World Cup Standings</TITLE>
<link rel="stylesheet" type="text/css" href="green_pagination.css">
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#800000" ALINK="#FF00FF" BACKGROUND="?">
		<h3>World Cup Standings Green</h3> <br>
		<div>
			<table width="40%" class="tbl-generator-green">
				<tr>
					<th>Rank</th><th>Group A</th><th>Played</th><th>Wins</th><th>Draws</th><th>Loss</th><th>Points</th>
				</tr>
				
			  <?php while ($row = $result->fetch_assoc()): ?>
			  <tr>			  
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $row['played']; ?></td>
				<td><?php echo $row['wins']; ?></td>
				<td><?php echo $row['loss']; ?></td>
				<td><?php echo $row['draws']; ?></td>
				<td><?php echo $row['points']; ?></td>		
			  </tr>
			  <?php endwhile; ?>		  
			</table>
			<br>
			
			<?php 
			if ($next_set > $max_pages_to_display) {			
				$next_set=$total_pages+10;
				$total_pages=$max_pages_to_display;
			?>
			<ul class="pagination modal-2">	  
				<?php if ($page == 0) { $page=1; }
					  elseif ($page < 20 and $page >= 11) { $total_pages = 10; $prev_set = 1; }
					  elseif ($page < 30 and $page >= 21) { $total_pages = 20; $prev_set = 11; }
					  elseif ($page < 40 and $page >= 31) { $total_pages = 30; $prev_set = 21; }
					  elseif ($page < 50 and $page >= 41) { $total_pages = 40; $prev_set = 31; }	
					  elseif ($page < 60 and $page >= 51) { $total_pages = 50; $prev_set = 41; }
					  elseif ($page < 70 and $page >= 61) { $total_pages = 60; $prev_set = 51; }
					  elseif ($page < 80 and $page >= 71) { $total_pages = 70; $prev_set = 61; }
					  elseif ($page < 90 and $page >= 81) { $total_pages = 80; $prev_set = 71; }
					  elseif ($page < 100 and $page >= 91) { $total_pages = 90; $prev_set = 81; }						  
				?>
				<li class="page" title="Prev Set"><a href="green_tables_pagination.php?page=<?php echo $prev_set ?>"><?php echo "Prev Set" ?></a></li>
				<?php if ($page == 0) { $page=1; }
					  elseif ($page >  0 and $page < 11) { $total_pages = 10; $page =  1; }
					  elseif ($page > 10 and $page < 21) { $total_pages = 20; $page = 11; }
					  elseif ($page > 20 and $page < 31) { $total_pages = 30; $page = 21; }
					  elseif ($page > 30 and $page < 41) { $total_pages = 40; $page = 31; }
					  elseif ($page > 40 and $page < 51) { $total_pages = 50; $page = 41; }
					  elseif ($page > 50 and $page < 61) { $total_pages = 60; $page = 51; }
					  elseif ($page > 60 and $page < 71) { $total_pages = 70; $page = 61; }
					  elseif ($page > 70 and $page < 81) { $total_pages = 80; $page = 71; }
					  elseif ($page > 80 and $page < 91) { $total_pages = 90; $page = 81; }
					  elseif ($page > 90 and $page < 101) { $total_pages = 100; $page = 91; }					  
				while ($page < $total_pages): 
					if ($page <= $max_tot_pages) { 
						if ($clicked_page != $page) { ?>
							<li class="page"><a href="green_tables_pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>			  				
				<?php	} elseif ($clicked_page == $page) { ?>
							<li class="active"><a href="green_tables_pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>
				<?php	}
					}				 					
				if ($page < $total_pages) {
					$page=$page+1;						
				} 	
				endwhile; 
				if ($page <= $max_tot_pages) {?>
					<li class="page"><a href="green_tables_pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>
				
				<?php $page=$page+1; ?>
					<li class="page" title="Next Set"><a href="green_tables_pagination.php?page=<?php echo $page ?>"><?php echo "Next Set" ?></a></li>
				<?php } ?>
			</ul>	
			<?php } 
			?>			
			
		</div>
</BODY>
</HTML> 