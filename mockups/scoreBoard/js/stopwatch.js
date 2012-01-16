window.newMins = 20;
window.newSecs = 0;

window.halftime = 1;

function toggleHalf()
{
	btnStart = document.getElementById("btnStart");  
  btnStart.innerHTML = "Start 2nd halftime";
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
		
		if (window.halftime == 1 && minutes >= window.newMins / 2){
			if (seconds >= window.newSecs / 2){
				$("#time").stopwatch().stopwatch('stop');
				toggleHalf();
				window.halftime = 2;			
			}				
		}		
			
		else if (window.halftime == 2 && minutes >= window.newMins){
			if (seconds >= window.newSecs){
				$("#time").stopwatch().stopwatch('stop');
				//funct for sending results		
			}			
		}
		return [pad2(minutes), pad2(seconds)].join(':') + ',' + pad2(millis);
		
}   

function toggle ()
{
  
  btnStart = document.getElementById("btnStart");  
	if (btnStart.innerHTML == "Start" || btnStart.innerHTML == "Resume" || btnStart.innerHTML == "Start 2nd halftime"){
    btnStart.innerHTML = "Pause";
		$("#time").stopwatch({formatter: format, updateInterval: 50}).stopwatch('start');
    return;
  }
  else if (btnStart.innerHTML == "Pause"){
    btnStart.innerHTML = "Resume";
		$("#time").stopwatch().stopwatch('stop');
		return;
  }
}
                                                                      
function resetTime()
{
  btnStart = document.getElementById("btnStart");	
	if (btnStart.innerHTML == "Resume" || btnStart.innerHTML == "Pause") {
    $("#time").stopwatch().stopwatch('stop');		
    $("#time").stopwatch().stopwatch('reset');
    $("#time").html("00:00,00");
    btnStart.innerHTML = "Start";
  }
  return;
}

function newTime()
{
	var inpMins = $('#fmins').val();
	var inpSecs = $('#fsecs').val();
	
	window.newMins = inpMins;
	window.newSecs = inpSecs;
	$('.saved').fadeIn(200).delay(500).fadeOut(200);


	return false;
}


