//Create Elements
var body = document.getElementsByTagName('body')[0];
var progress = document.createElement("progress");
var span = document.createElement("span");
progress.setAttribute("id", "timeLeft__progress");
span.setAttribute("id", "timeLeft");

//Apending
body.appendChild(progress);
body.appendChild(span);

//some variables
var progressbar = document.getElementById('timeLeft__progress');
var timeLeft = document.getElementById('timeLeft');
var from = parseInt(options.from + "000");
var to = parseInt(options.to + "000");
var eventName = options.eventName;
var time = new Date();
var currentTime = time.getTime();
var position = "timeLeft-" + options.position;
progress.setAttribute("class", position);
span.setAttribute("class", position);

//some easy math
progressbar.value = currentTime - from;
progressbar.max = to - from;

var daysLeft = Math.floor((progressbar.max - progressbar.value)/86400000);
var hoursLeft = Math.floor(((progressbar.max - progressbar.value) - daysLeft * 86400000)/3600000);
var minutesLeft = Math.ceil((((progressbar.max - progressbar.value) - daysLeft * 86400000) - hoursLeft * 3600000)/60000);


//display the progress bar
function update() {
	currentTime = time.getTime();
	timeLeft.innerHTML = daysLeft + 'days, ' + hoursLeft + 'hours, ' + minutesLeft + 'minutes left to '+ eventName +'.';
}

setInterval(update, 10000);
update();