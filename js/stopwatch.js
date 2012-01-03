function toggle ()
{
  btnStart = document.getElementById("btnStart");  
	if (btnStart.innerHTML == "Start" || btnStart.innerHTML == "Resume"){
    btnStart.innerHTML = "Pause";
		$("#time").stopwatch().stopwatch('start');
    return;
  }
  if (btnStart.innerHTML == "Pause" || btnStart.innerHTML == "Resume"){
    btnStart.innerHTML = "Resume";
		$("#time").stopwatch().stopwatch('stop');
		return;
  }
}

function resetTime()
{
  btnStart = document.getElementById("btnStart");	
	$("#time").stopwatch().stopwatch('stop');		
	$("#time").stopwatch().stopwatch('reset');
	$("#time").html("00:00:00");
	btnStart.innerHTML = "Start";
}


