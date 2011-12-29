function toggle()
{
  if ((document.getElementById("btnStart").innerHTML == "Start") || 
					(document.getElementById("btnStart").innerHTML == "Resume")){
    document.getElementById("btnStart").innerHTML = "Pause";
		$("#time").stopwatch().stopwatch('start');
    return;
  }
  if (document.getElementById("btnStart").innerHTML == "Pause"){
    document.getElementById("btnStart").innerHTML = "Resume";
		$("#time").stopwatch().stopwatch('stop');
		return;
  }
}
function resetTime()
{
	$("#time").stopwatch().stopwatch('stop');		
	$("#time").stopwatch().stopwatch('reset');
	$("#time").html("00:00:00");
	document.getElementById("btnStart").innerHTML = "Start";
}
