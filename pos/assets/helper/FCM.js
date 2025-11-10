var FCM = function(){
    var apiKey = null;
    var configFcm = null;
    var messaging = null;
    var myToken = null;
    return {
        setConfig: function (configsett) {
            var config = JSON.parse(atob(atob(configsett)));
            config = $.extend(true, {
                apiKey: null,
                authDomain: null,
                databaseURL: null,
                projectId: null,
                storageBucket: null,
                messagingSenderId: null,
                appId: null,
                measurementId: null,
            }, config);
            configFcm = config;

            if (!firebase.apps.length && firebase.messaging.isSupported()) {
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/appfms/firebase-messaging-sw.js', {
                        scope:'/appfms/'
                    }).then(function(registration) {
                        console.log('Registration successful, scope is:', registration.scope);
                    }).catch(function(err) {
                        console.log('Service worker registration failed, error:', err);
                    });
                }
                firebase.initializeApp(configFcm);
                messaging = firebase.messaging();
                FCM.setOnMessage();
            }

        },

        getConfig: function () {
            return configFcm;
        },

        getMyToken: function () {
            return myToken;
        },

        initialize: function () {
            /*if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/learningexperience/eu/firebase-messaging-sw.js', {
                    scope:'/learningexperience/eu/'
                })
                .then(function(registration) {
                    console.log('Registration successful, scope is:', registration.scope);
                }).catch(function(err) {
                    console.log('Service worker registration failed, error:', err);
                });
            }*/
            /*firebase.initializeApp(FCM.getConfig());
            messaging = firebase.messaging();
            messaging.usePublicVapidKey(FCM.getConfig().usePublicVapidKey);
            FCM.reqPermission();
            FCM.setOnMessage();*/
        },

        reqPermission: function (config) {
            config = $.extend(true,{
                callback: function(){}
            },config);

            if (firebase.messaging.isSupported()) {
                Notification.requestPermission().then((permission) => {
                    if (permission === 'granted') {
                        config.callback(true);
                    } else {
                        config.callback(false);
                    }
                });
            }else{
                config.callback(false);
            }
        },

        getToken: function (config) {
            config = $.extend(true,{
                callback: function(){}
            },config);

            messaging.getToken().then((currentToken) => {
                if (currentToken) {
                    var res = {success: true, 'message': 'Success get token.', 'token': currentToken};
                    myToken = currentToken;
                    config.callback(res);
                } else {
                    var res = {success: false, 'message': 'No Instance ID token available. Request permission to generate one.', 'token': null};
                    myToken = null;
                    config.callback(res);
                }
            }).catch((err) => {
                console.log('error',err);
                var res = {success: false, 'message': 'An error occurred while retrieving token.', 'token': null};
                myToken = null;
                config.callback(res);
            });
            
        },

        deleteToken: function (config) {
            config = $.extend(true,{
                callback: function(){}
            },config);

            if (firebase.messaging.isSupported()) {
                messaging.getToken().then((currentToken) => {
                    messaging.deleteToken(currentToken).then(() => {
                        myToken = null;
                        config.callback({success: true, message: 'Token Deleted'});
                    }).catch((err) => {
                        myToken = null;
                        config.callback({success: false, message: 'Unable to delete token.'});
                    });
                }).catch((err) => {
                    myToken = null;
                    config.callback({success: false, message: 'Error retrieving Instance ID token.'});
                });
            }else{
                config.callback({success: false, message: 'Not supported fcm.'});
            }
        },

        setOnMessage: function () {
            if (firebase.messaging.isSupported()) {
                messaging.onMessage((payload) => {

                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-bottom-right",
                        "preventDuplicates": false,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "3000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        "tapToDismiss": false
                    };

                    var data = payload.data;

                    data = $.extend(true,{
                        is_unseen: "true",
                        type: "info"
                    },data);
                    /*if (data.is_unseen == "true") {
                        load_unseen_notification();
                    }*/

                    if (data.type == "success") {
                        toastr.success(payload.data.body, payload.data.title);
                    }else if(data.type == "error"){
                        toastr.error(payload.data.body, payload.data.title);
                    }else if(data.type == "warning"){
                        toastr.warning(payload.data.body, payload.data.title);
                    }else if(data.type == "info"){
                        toastr.info(payload.data.body, payload.data.title);
                    }else{
                        toastr.info(payload.data.body, payload.data.title);
                    }
                    
                });
            }
        },
    }
}();