<?php
require_once 'vendor/autoload.php';
use Subash\Classes\Player;
use Subash\Classes\Teams;
setlocale(LC_MONETARY, 'en_US');
$player = new Player ();
$teams = new Teams();
$playerid = $_GET ['pid'];
$players = $player->getPlayerDetails ( $playerid );
$playerSummary= $player->getPlayerSummary ( $playerid );
//print_r($playerSummary);exit;
$playerSummary = $playerSummary[0];
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
<br/>
<br/>
	<div class="input-group">
		<div class="ui-widget">
			<input type="text" id="search" name="search" class="form-control"
				placeholder="Search ..."> <input type="hidden" id="search_id"
				name="search_id" />
		</div>
		<span class="input-group-btn">
			<button class="btn btn-default" type="button" id="search_go">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</span>
	</div>

<?php 
$pName = '';
if(count($players) > 0)
{
	$pName = $players[0]['player_name'];
}
?>
	<h4>Search Results for <?php echo $pName;?></h4>
	<table width="75%">
	
	<?php 
	if(count($players) > 0)
	{
		$pl = $players[0];
		$teamId = $pl['team'];
		$nextMatches = $teams->getUpcomingmatches($teamId);
		$lastMatches = $teams->getLastmatches($teamId);
			echo "<tr>";
			/**
			 * Latest Price
			 */
			echo "<td>";
			echo money_format('%i',(doubleval($pl['player_price'])));
			echo "</td>";
			
			/**
			 * Last 3 round avarage
			 */
			echo "<td>";
			echo "3 round Ave: ".money_format("%i",($players[0]['player_price']+$players[0]['player_price']+$players[0]['player_price']/3));
			echo "</td>";
			
			/**
			 * Last 5 round avarage
			 */
			echo "<td>";
			echo "5 round Ave: ".money_format("%i",($players[0]['player_price']+$players[1]['player_price']+$players[2]['player_price']+$players[3]['player_price']+$players[4]['player_price']/5));
			
			echo "</td>";
			
			/**
			 * Need to check
			 */
			echo "<td>";
			echo "Season ave: X";
			echo "</td>";
			
			echo "<td>";
			echo "Previous 3";
			echo "</td>";
			
			echo "<td>";
			echo "Previous 3 Score";
			echo "</td>";
			
			echo "<td>";
			echo "Next 3";
			echo "</td>";
			
			echo "</tr>";
			
			/**
			 * Row one ends here
			 * Row2 start here
			 */
			echo "<tr>";
			/**
			 * PSD: Price current – price previous
			 */
			echo "<td>";
			echo ($players[0]['player_price'] - $players[1]['player_price']);
			echo "</td>";
			/**
			 * Min: PSD: Lowest score (where year = 2015)
			 */	
			echo "<td>";
			echo "Min : ".$playerSummary['min_player_score_val'];
			echo "</td>";
			
			/**
			 * Points per dollar: Average(2015)/Current Price
			 */
			echo "<td>";
			echo  "Points per dollar :  " .money_format("%i",$playerSummary['avg_player_price'] / $players[0]['player_price']);
			echo "</td>";
			
			echo "<td>";
			echo $pl['player_price'];
			echo "</td>";
			
			/**
			 * F&R: Last fixture for player club club = player club. Date, opponent.

			 */
			echo "<td>";
			$str = '';
			
			if(isset($lastMatches[0]))
			{
				$str .= $pl['team_name']. " ,";
				$str .= $lastMatches[0]['match_date']. " ,";
				if(strtolower($pl['team_name']) == strtolower($lastMatches[0]['team1_name']))
				{
					$str .= $lastMatches[0]['team2_name'];
				}else{
					$str .= $lastMatches[0]['team1_name'];
					}
					
			}
			echo $str;
			echo "</td>";
			
			/**
			 * Last score
			 */
			echo "<td>";
			$str = '';
				
			if(isset($lastMatches[0]))
			{
				if(strtolower($pl['team_name']) == strtolower($lastMatches[0]['team1_name']))
				{
					$str .= $lastMatches[0]['team1_score']."-".$lastMatches[0]['team2_score'];
				}else{
					$str .= $lastMatches[0]['team2_score']."-".$lastMatches[0]['team1_score'];
				}
			}
			echo $str;
			echo "</td>";
			
			/**
			 * F&R: Next fixture for player club club = player club. Date, opponent.
			 */
			echo "<td>";
			$str = '';
			
			if(isset($nextMatches[0]))
			{
				$str .= $pl['team_name']. " ,";
				$str .= $nextMatches[0]['match_date']. " ,";
				if(strtolower($pl['team_name']) == strtolower($nextMatches[0]['team1_name']))
				{
					$str .= $nextMatches[0]['team2_name'];
				}else{
					$str .= $nextMatches[0]['team1_name'];
					}
					
			}
			echo $str;
			echo "</td>";
				
			
				
			echo "</tr>";
			
			/**
			 * Row3
			 */
			
			echo "<tr>";
			/**
			 * PSD: Price current – price previous
			 */
			echo "<td>";
			echo "";
			echo "</td>";
			/**
			 * Min: PSD: Lowest score (where year = 2015)
			 */
			echo "<td>";
			echo "Min : ".$playerSummary['max_player_score_val'];
			echo "</td>";
				
			/**
			 * Points per dollar: Average(2015)/Current Price
			 */
			echo "<td>";
			echo "";
			echo "</td>";
				
			echo "<td>";
			echo "";
			echo "</td>";
				
			/**
			 * F&R: Last fixture for player club club = player club. Date, opponent.
			
			 */
			echo "<td>";
			$str = '';
				
			if(isset($lastMatches[1]))
			{
				$str .= $pl['team_name']. " ,";
				$str .= $lastMatches[1]['match_date']. " ,";
				if(strtolower($pl['team_name']) == strtolower($lastMatches[1]['team1_name']))
				{
					$str .= $lastMatches[1]['team2_name'];
				}else{
					$str .= $lastMatches[1]['team1_name'];
				}
					
			}
			echo $str;
			echo "</td>";
				
			/**
			 * Last score
			 */
			echo "<td>";
			$str = '';
			
			if(isset($lastMatches[1]))
			{
				if(strtolower($pl['team_name']) == strtolower($lastMatches[1]['team1_name']))
				{
					$str .= $lastMatches[1]['team1_score']."-".$lastMatches[1]['team2_score'];
				}else{
					$str .= $lastMatches[1]['team2_score']."-".$lastMatches[1]['team1_score'];
				}
			}
			echo $str;
			echo "</td>";
				
			/**
			 * F&R: Next fixture for player club club = player club. Date, opponent.
			 */
			echo "<td>";
			$str = '';
				
			if(isset($nextMatches[1]))
			{
				$str .= $pl['team_name']. " ,";
				$str .= $nextMatches[1]['match_date']. " ,";
				if(strtolower($pl['team_name']) == strtolower($nextMatches[1]['team1_name']))
				{
					$str .= $nextMatches[1]['team2_name'];
				}else{
					$str .= $nextMatches[1]['team1_name'];
				}
					
			}
			echo $str;
			echo "</td>";
			
				
			
			echo "</tr>";
			
			
			/**
			 * Row4
			 */
			

			
				
			echo "<tr>";
			/**
			 * PSD: Price current – price previous
			 */
			echo "<td>";
			echo $pl['player_position'];
			echo "</td>";
			/**
			 * Min: PSD: Lowest score (where year = 2015)
			 */
			echo "<td>";
			echo "";
			echo "</td>";
			
			/**
			 * Points per dollar: Average(2015)/Current Price
			 */
			echo "<td>";
			echo "";
			echo "</td>";
			
			echo "<td>";
			echo "";
			echo "</td>";
			
			/**
			 * F&R: Last fixture for player club club = player club. Date, opponent.
			 	
			 */
			echo "<td>";
			$str = '';
			
			if(isset($lastMatches[2]))
			{
				$str .= $pl['team_name']. " ,";
				$str .= $lastMatches[2]['match_date']. " ,";
				if(strtolower($pl['team_name']) == strtolower($lastMatches[2]['team1_name']))
				{
					$str .= $lastMatches[2]['team2_name'];
				}else{
					$str .= $lastMatches[2]['team1_name'];
				}
					
			}
			echo $str;
			echo "</td>";
			
			/**
			 * Last score
			 */
			echo "<td>";
			$str = '';
				
			if(isset($lastMatches[2]))
			{
				if(strtolower($pl['team_name']) == strtolower($lastMatches[2]['team1_name']))
				{
					$str .= $lastMatches[2]['team1_score']."-".$lastMatches[2]['team2_score'];
				}else{
					$str .= $lastMatches[2]['team2_score']."-".$lastMatches[2]['team1_score'];
				}
			}
			echo $str;
			echo "</td>";
			
			/**
			 * F&R: Next fixture for player club club = player club. Date, opponent.
			 */
			echo "<td>";
			$str = '';
			
			if(isset($nextMatches[2]))
			{
				$str .= $pl['team_name']. " ,";
				$str .= $nextMatches[2]['match_date']. " ,";
				if(strtolower($pl['team_name']) == strtolower($nextMatches[2]['team1_name']))
				{
					$str .= $nextMatches[2]['team2_name'];
				}else{
					$str .= $nextMatches[2]['team1_name'];
				}
					
			}
			echo $str;
			echo "</td>";
				
			
				
			echo "</tr>";
		
	}
	?>
	</table>

	<br> <br>

</div>
</div>
</div>
</div>

<?php require_once 'footer.php';?>