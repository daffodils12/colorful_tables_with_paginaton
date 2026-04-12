 
<?php
$i=0;
$page=1;

// Below is optional, remove if you have already connected to your database.
$mysqli = mysqli_connect("localhost",'root','1234567890', 'tutorials');

// Number of results to show on each page.
$num_results_on_page = 20;

//Max nr of pages to display
$max_pages_to_display=10;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // SAVE changes
    if (!empty($_POST['changed'])) {
        foreach ($_POST['changed'] as $rowid => $val) {
            // update DB
						
			$played = $_POST['played'][$rowid];
			$wins = $_POST['wins'][$rowid];
			$loss = $_POST['loss'][$rowid];
			$draws = $_POST['draws'][$rowid];
			$points = $_POST['points'][$rowid];
			$rowsid = $rowid;

			// DEBUG (you can remove later)
			//echo "Updating ID: $id → Wins: $wins <br>";

			// TODO: UPDATE DB HERE
			$qry_str='UPDATE worldcup_standings set played='.$played.',wins='.$wins.',loss='.$loss.',draws='.$draws.',points='.$points.' where id='.$rowsid;
			
			echo "<script>console.log('$qry_str :'.$qry_str);</script>";
			$mysqli->query($qry_str);
						
		}
		$mysqli->query('commit');
		$_GET['page'] = $page;
    }

    // get page AFTER submit
    $page = (int)($_POST['page'] ?? 1);

} else {
    // normal navigation
    $page = (int)($_GET['page'] ?? 1);
}	

$cOrder = $_GET['colOrder']  ?? '1';  // default to 1
$sOrder = $_GET['sortOrder'] ?? 'A';  // default to A

$prev_set=1;
$next_set=11;
$clicked_page=1;

// Get the total number of records from our table "worldcup_standings".
$total_pages = round($mysqli->query('SELECT * FROM worldcup_standings')->num_rows/$num_results_on_page);
$max_tot_pages = $total_pages;

// Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
$display_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$clicked_page=$page;
?>
 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
<TITLE>World Cup Standings</TITLE>
<link rel="stylesheet" type="text/css" href="green_pagination_no_borders_8.css">
<script src="https://kit.fontawesome.com/7478087893.js" crossorigin="anonymous"></script>
<script>
let oldVal = {};

function storeOldValue(el) {
    oldVal[el.name] = el.value;
	el.style.background="Yellow";
}

function ChngBkGrndClr(el) {
	el.style.background="white";
}

function valueChange(element) {

    let currVal = parseInt(element.value, 10);
    let old = parseInt(oldVal[element.name], 10);
	//alert("oldVal[element.name] "+oldVal[element.name]);

    // extract ID from name="wins[123]"
    let id = element.name.match(/\[(.*?)\]/)[1];

    let checkboxName = "changed[" + id + "]";
    let checkbox = document.getElementsByName(checkboxName)[0];

    if (checkbox) {
        checkbox.checked = (!isNaN(old) && old !== currVal);
    }
}

</script>
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#800000" ALINK="#FF00FF" BACKGROUND="?">
	
	<form name="mainForm" id="mainForm" action="green_tables_pagination_no_borders_8.php" method="post">
		<h3 align="center" style="color:MediumSeaGreen;">World Cup Standings Green</h3> <br>
			
		<?php 
			function toggleAscDesc($sOrder) {
				//var_dump($sOrder);
				if ($sOrder == "A") {
					echo '<i class="fas fa-sort-amount-down" style="font-size:16px;;color:white"></i>';
				} else {
					echo '<i class="fas fa-sort-amount-up-alt"   style="font-size:16px;;color:white"></i>'; 
				}
			}
		?>		
	
			<table width=60%; class="tbl-generator-green">
			  <colgroup>
				<col class="column1">
				<col class="column2">
				<col class="column3">
				<col class="column4">
				<col class="column5">
				<col class="column6">
				<col class="column7">
			  </colgroup>				   
				<tr>
					<th>
						<a href="#" title="Sort by Rank" onclick="toggleSort(1); return false;" style="color:white;">
							Rank <?php toggleAscDesc($sOrder); ?>
						</a>												
					</th>
					<th>
						<a href="#" title="Sort by Country" onclick="toggleSort(2); return false;" style="color:white;">
							Country  <?php toggleAscDesc($sOrder); ?>
						</a>							
					</th>
					<th>	<a href="#" title="Sort by Played" onclick="toggleSort(3); return false;" style="color:white;">
							Played <?php toggleAscDesc($sOrder); ?>
						</a>
					</th>
					<th>
						<a href="#" title="Sort by Wins" onclick="toggleSort(4); return false;" style="color:white;">
							Wins <?php toggleAscDesc($sOrder); ?>
						</a>
					</th>
					<th>	<a href="#" title="Sort by Draws" onclick="toggleSort(5); return false;" style="color:white;">
							Draws <?php toggleAscDesc($sOrder); ?>
						</a>
					</th>
					<th>	<a href="#" title="Sort by Loss" onclick="toggleSort(6); return false;" style="color:white;">
							Loss <?php toggleAscDesc($sOrder); ?>
						</a>
					</th>					
					<th>    <a href="#" title="Sort by Points" onclick="toggleSort(7); return false;" style="color:white;">
							Points <?php toggleAscDesc($sOrder); ?>
						</a>
					</th>
				</tr>
				
				<script>
					function toggleSort(colOrder) {
						let urlParams = new URLSearchParams(window.location.search);
						//alert("sortOrder :"+urlParams.get("sortOrder"));
						let current = urlParams.get("sortOrder") || "A";
						let next = (current === "A") ? "D" : "A";

						let page = urlParams.get("page") || 1;

						window.location.href = "green_tables_pagination_no_borders_8.php?page=" + page + "&sortOrder=" + next + "&colOrder=" + colOrder ;
					}		
				</script>					
			
			<?php	
			$sOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'A';
			$sOrder = ($sOrder === 'D') ? 'D' : 'A'; // safety
			$order = ($sOrder === 'D') ? 'DESC' : 'ASC';
			
			$corder = isset($_GET['colOrder']) ? $_GET['colOrder'] : '1';
			
			$display_page = $_POST['page'] ?? ($_GET['page'] ?? 1);			

			$qry = "SELECT * FROM worldcup_standings ORDER BY $corder $order LIMIT ?,?";

			if ($stmt = $mysqli->prepare($qry)) {
				// Calculate the page to get the results we need from our table.
				$calc_page = ($display_page - 1) * $num_results_on_page;
				$stmt->bind_param('ii', $calc_page, $num_results_on_page);
				$stmt->execute(); 
				// Get the results...
				$result = $stmt->get_result();
				}			

			  while ($row = $result->fetch_assoc()): ?>
			  <tr>		
				<input name="changed[<?= $row['id'] ?>]"  type="checkbox" value="on" /> <!-- add hidden finally-->
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['Country']; ?></td>
				<td><input name="played[<?= $row['id'] ?>]" type="number" onfocus="storeOldValue(this)" onBlur="ChngBkGrndClr(this)" oninput="valueChange(this,<?= $i ?>)" min="0" max="999" style="width: 7ch;" pattern="[0-9]*"  step="1" title="Only between 0 to 999" value=<?php echo $row['Played']; ?> > </input></td>
				<td><input name="wins[<?= $row['id'] ?>]"   type="number" onfocus="storeOldValue(this)" onBlur="ChngBkGrndClr(this)" oninput="valueChange(this,<?= $i ?>)" min="0" max="999" style="width: 7ch;" pattern="[0-9]*"  step="1" title="Only between 0 to 999" value=<?php echo $row['Wins']; ?> > </input> </td> 
				<td><input name="loss[<?= $row['id'] ?>]"   type="number" onfocus="storeOldValue(this)" onBlur="ChngBkGrndClr(this)" oninput="valueChange(this,<?= $i ?>)" min="0" max="999" style="width: 7ch;" pattern="[0-9]*"  step="1" title="Only between 0 to 999" value=<?php echo $row['Loss']; ?> > </input> </td>
				<td><input name="draws[<?= $row['id'] ?>]"  type="number" onfocus="storeOldValue(this)" onBlur="ChngBkGrndClr(this)" oninput="valueChange(this,<?= $i ?>)" min="0" max="999" style="width: 7ch;" pattern="[0-9]*"  step="1" title="Only between 0 to 999" value=<?php echo $row['Draws']; ?> > </input> </td>
				<td><input name="points[<?= $row['id'] ?>]" type="number" onfocus="storeOldValue(this)" onBlur="ChngBkGrndClr(this)" oninput="valueChange(this,<?= $i ?>)" min="0" max="999" style="width: 7ch;" pattern="[0-9]*"  step="1" title="Only between 0 to 999" value=<?php echo $row['Points']; ?> > </input> </td>		
			  </tr>	  			  
				<?php $i++; ?>
			  <?php endwhile; ?>		  
			</table>
			<br>
				
			<?php 
			if ($next_set > $max_pages_to_display) {			
				$next_set=$total_pages+10;
				$total_pages=$max_pages_to_display;
			?>
			<ul class="pagination modal-2" align="center-list">	  
				<?php if ($display_page == 0) { $display_page=1; }
					  elseif ($display_page <= 20 and $display_page >= 11) { $total_pages = 10; $prev_set = 1; }
					  elseif ($display_page <= 30 and $display_page >= 21) { $total_pages = 20; $prev_set = 11; }
					  elseif ($display_page <= 40 and $display_page >= 31) { $total_pages = 30; $prev_set = 21; }
					  elseif ($display_page <= 50 and $display_page >= 41) { $total_pages = 40; $prev_set = 31; }	
					  elseif ($display_page <= 60 and $display_page >= 51) { $total_pages = 50; $prev_set = 41; }
					  elseif ($display_page <= 70 and $display_page >= 61) { $total_pages = 60; $prev_set = 51; }
					  elseif ($display_page <= 80 and $display_page >= 71) { $total_pages = 70; $prev_set = 61; }
					  elseif ($display_page <= 90 and $display_page >= 81) { $total_pages = 80; $prev_set = 71; }
					  elseif ($display_page <= 100 and $display_page >= 91) { $total_pages = 90; $prev_set = 81; }						  
				?>				
				<!-- <li class="modal-2" title="Prev Set"><a href="green_tables_pagination_no_borders_8.php?page=<?php echo $prev_set ?>" onclick="gotoPage(<?= $display_page ?>); return false;"><?php echo "Prev Set" ?></a></li> -->
				<!-- <li class="modal-2" title="Prev Set"><a href="#" onclick="gotoPage(<?= $display_page ?>); return false;"><?php echo "Prev Set" ?></a></li>	-->			
				<li class="modal-2" title="Prev Set"><a href="#" onclick="gotoPage(<?= $prev_set ?>); return false;"><?php echo "Prev Set" ?></a></li>
				<?php if ($display_page == 0) { $display_page=1; }
					  elseif ($display_page >  0 and $display_page < 11) { $total_pages = 10; $display_page =  1; }
					  elseif ($display_page > 10 and $display_page < 21) { $total_pages = 20; $display_page = 11; }
					  elseif ($display_page > 20 and $display_page < 31) { $total_pages = 30; $display_page = 21; }
					  elseif ($display_page > 30 and $display_page < 41) { $total_pages = 40; $display_page = 31; }
					  elseif ($display_page > 40 and $display_page < 51) { $total_pages = 50; $display_page = 41; }
					  elseif ($display_page > 50 and $display_page < 61) { $total_pages = 60; $display_page = 51; }
					  elseif ($display_page > 60 and $display_page < 71) { $total_pages = 70; $display_page = 61; }
					  elseif ($display_page > 70 and $display_page < 81) { $total_pages = 80; $display_page = 71; }
					  elseif ($display_page > 80 and $display_page < 91) { $total_pages = 90; $display_page = 81; }
					  elseif ($display_page > 90 and $display_page < 101) { $total_pages = 100; $display_page = 91; }					  
				while ($display_page < $total_pages): 
					if ($display_page <= $max_tot_pages) { 
						if ($clicked_page != $display_page) { ?>
							<!-- <li class="page"><a href="green_tables_pagination_no_borders_8.php?page=<?php echo $display_page ?>" onclick="gotoPage(<?= $display_page ?>); return false;"><?php echo $display_page ?></a></li> -->
							<li class="page"><a href="#" onclick="gotoPage(<?= $display_page ?>); return false;"><?= $display_page ?></a></li>
				<?php	} 
						elseif ($clicked_page == $display_page) { ?>
							<!-- <li class="page"><a class="active" href="green_tables_pagination_no_borders_8.php?page=<?php echo $display_page ?>" onclick="gotoPage(<?= $display_page ?>); return false;"><?php echo $display_page ?></a></li> -->
							<li class="page"><a class="active" href="#" onclick="gotoPage(<?= $display_page ?>); return false;"><?= $display_page ?></a></li>
				<?php	}
					}				 					
				if ($display_page < $total_pages) {
					$display_page=$display_page+1;						
				} 				 		
				endwhile;
				
				/*echo "page :" . $display_page;
				echo "clicked_page :" . $clicked_page;				
				echo "max_tot_pages :" . $max_tot_pages;*/		
				
				if ($display_page <= $max_tot_pages) {
				  if ($clicked_page == $display_page) { ?> 
					<!-- <li class="page"><a class="active" href="green_tables_pagination_no_borders_8.php?page=<?php echo $display_page ?>"><?php echo $display_page ?></a></li> -->
					<li class="page"><a class="active" href="#" onclick="gotoPage(<?= $display_page ?>); return false;"><?= $display_page ?></a></li>
				  <?php } 
				  elseif ($clicked_page <= $max_tot_pages) {  ?> 
					<!-- <li class="page"><a href="green_tables_pagination_no_borders_8.php?page=<?php echo $display_page ?>"><?php echo $display_page ?></a></li> -->
					<li class="page"><a href="#" onclick="gotoPage(<?= $display_page ?>); return false;"><?= $display_page ?></a></li>
				  <?php }					 				 
				}
				if ($clicked_page == $max_tot_pages and $display_page <= $clicked_page) { ?> 
					<!-- <li class="page"><a class="active" href="green_tables_pagination_no_borders_8.php?page=<?php echo $display_page ?>"><?php echo $display_page ?></a></li> -->
					<li class="page"><a class="active" href="#" onclick="gotoPage(<?= $display_page ?>); return false;"><?= $display_page ?></a></li>
				<?php } 
				if ($display_page <= $max_tot_pages) { $display_page=$display_page+1; ?>
					<!-- <li class="modal-2" title="Next Set"><a href="green_tables_pagination_no_borders_8.php?page=<?php echo $display_page ?>" onclick="gotoPage(<?= $display_page ?>); return false;"><?php echo "Next Set" ?></a></li>		-->			
					<li class="modal-2" title="Next Set"><a href="#" onclick="gotoPage(<?= $display_page ?>); return false;"><?php echo "Next Set" ?></a></li>
				<?php } ?>
			</ul>
			<?php } 
			
			?>
			
			
			<br/>	
			<input type="text" name="page" id="page" value="<?= ($page); ?>" ></input>
			<input type="Submit" onclick="alert('Button clicked!')" hidden> </input>		
			
			<script>
			function gotoPage(page) {
				//alert("submitting New page #:" + page);
				
				// set page value in hidden field 
				document.getElementById("page").value = page;	
				document.forms["mainForm"].submit();
			}
			</script>				
	</form>
</BODY>
</HTML> 