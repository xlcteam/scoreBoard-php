var goal1 = 0;
var goal2 = 0;

function toggleOnGoal()
{
	btnStart = document.getElementById("btnStart");
	$("#time").stopwatch().stopwatch('stop');
	
	if (btnStart.innerHTML == "Pause"){
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
