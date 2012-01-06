var goal1 = 0;
var goal2 = 0;

function toggleOnGoal()
{
	btnStart = document.getElementById("btnStart");
	if (btnStart.innerHTML == "Pause"){
	    $("#time").stopwatch().stopwatch('stop');
		btnStart.innerHTML = "Resume";
	}
}

function team1Goal()
{
	goal1++;
	toggleOnGoal();
	$("#team1").html(goal1);
}

function team2Goal()
{
	goal2++;
	toggleOnGoal();
	$("#team2").html(goal2);
}

function resetScore()
{
	goal1 = 0;
	goal2 = 0;
	$('#team1').html(goal1);
	$('#team2').html(goal2);
}
