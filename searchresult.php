<?php
require_once 'vendor/autoload.php';
use Subash\Classes\Player;

$player = new Player ();
$playerid = $_GET ['pid'];
$players = $player->getPlayerDetails ( $playerid );

require_once 'header.php';
?>
<style>
<!--
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}
-->
</style>
<div class="col-sm-9">

	<hr>
<?php 
$pName = '';
if(count($players) > 0)
{
	$pName = $players[0]['player_name'];
}
?>
	<h4>Search Results for <?php echo $pName;?></h4>
	<table width="75%">
	<tr>
	 <th>Year</th>
	 <th>Round</th>
	 <th>Score</th>
	 <th>Price</th>
	 
	</tr>
	<?php 
	if(count($players) > 0)
	{
		foreach ($players as $pl)
		{
			echo "<tr>";
			echo "<td>";
			echo $pl['player_score_year'];
			echo "</td>";
			
			echo "<td>";
			echo $pl['player_round'];
			echo "</td>";
			
			echo "<td>";
			echo $pl['player_score_val'];
			echo "</td>";
			
			echo "<td>";
			echo $pl['player_price'];
			echo "</td>";
			
			echo "</tr>";
		}
	}
	?>
	</table>

	<br> <br>

</div>
</div>
</div>
</div>

<?php require_once 'footer.php';?>