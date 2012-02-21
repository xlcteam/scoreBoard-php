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
	//toggleOnGoal();
	$("#team1").html(goal1);
	$("#team1").effect("scale", { percent: 150}, 1000)
               .effect("scale", { percent: Math.ceil(100 / (150 / 100))}, 1000);
}

function team2Goal()
{
	goal2++;
	//toggleOnGoal();
	$("#team2").html(goal2);
	$("#team2").effect("scale", { percent: 150}, 1000)
               .effect("scale", { percent: Math.ceil(100 / (150 / 100))}, 1000);
}

function team1Down()
{
	if (goal1 <= 0){
		goal1 = 0;
		return false;	
	}else{
		goal1--;
		$("#team1").html(goal1);	
		return false;
	}
}

function team2Down()
{
	if (goal2 <= 0){
		goal2 = 0;
		return false;	
	}else{
		goal2--;
		$("#team2").html(goal2);
		return false;	
	}
}

function resetScore()
{
	goal1 = 0;
	goal2 = 0;
	$('#team1').html(goal1);
	$('#team2').html(goal2);
}
