var goal1 = 0;
var goal2 = 0;

function team1Goal()
{
	goal1++;
	if (document.getElementById("btnStart").innerHTML == "Stop"){
    		document.getElementById("btnStart").innerHTML = "Start";
	}
	$("#team1").html(goal1);
}

function team2Goal()
{
	goal2++;
	if (document.getElementById("btnStart").innerHTML == "Stop"){
    		document.getElementById("btnStart").innerHTML = "Start";
	}
	$("#team2").html(goal2);
}
