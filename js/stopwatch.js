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
