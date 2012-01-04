function toggle ()
{
  btnStart = document.getElementById("btnStart");  
	if (btnStart.innerHTML == "Start" || btnStart.innerHTML == "Resume"){
    btnStart.innerHTML = "Pause";
		$("#time").stopwatch({formatter: format, updateInterval: 50}).stopwatch('start');
    return;
  }
  if (btnStart.innerHTML == "Pause" || btnStart.innerHTML == "Resume"){
    btnStart.innerHTML = "Resume";
		$("#time").stopwatch().stopwatch('stop');
		return;
  }
}

function pad2(number) {                                                
     return (number < 10 ? '0' : '') + number;                         
}                                                                      

function format(millis) {                                              
    var seconds, minutes;                                              
    minutes = Math.floor(millis / 60000);                              
    millis %= 60000;                                                   
    seconds = Math.floor(millis / 1000);                               
    millis = Math.floor(millis % 1000);                                
    millis = Math.floor(millis / 10);                                  
    return [pad2(minutes), pad2(seconds)].join(':') + ',' + pad2(millis);
}   

function resetTime()
{
  btnStart = document.getElementById("btnStart");	
	$("#time").stopwatch().stopwatch('stop');		
	$("#time").stopwatch().stopwatch('reset');
	$("#time").html("00:00,00");
	btnStart.innerHTML = "Start";
  return;
}


