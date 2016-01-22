/**
 * 
 */
$(document).ready(
		function() {
			$("#search").autocomplete({
				source : 'search.php',
				focus : function(event, ui) {
					console.log(ui);
					console.log(ui.item.player_name);
					$("#search").val(ui.item.player_name);
					return false;
				},
				select : function(event, ui) {
					$("#search").val(ui.item.player_name);
					$("#search_id").val(ui.item.player_id);

					return false;
				}
			}).autocomplete("instance")._renderItem = function(ul, item) {
				return $("<li>").append(
						"<a><span style='float:left'>" + item.player_name
								+ "</span><span style='text-align:center;padding-left: 100px;'>"
								+ item.team_name
								+ "</span><span style='float:right'>"
								+ item.team_name + "</span></a>").appendTo(ul);
			};

			$("#search_go").click(
					function() {

						window.location = "searchresult.php?pid="
								+ $("#search_id").val();
					});
		});