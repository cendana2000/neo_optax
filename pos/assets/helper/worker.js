/*var worker = (function(){
	if (!('Notification' in window)) {
	  console.log('This browser does not support notifications!');
	  return;
	}

	Notification.requestPermission(function(status) {
	  console.log('Notification permission status:', status);
	});

	if (Notification.permission == 'granted') {
		navigator.serviceWorker.getRegistration().then(function(reg) {	  
	  		reg.showNotification('Hello world!');
	  	});
	}
})();*/