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
                  // console.log(APP_DIR + 'assets/import/fcm/firebase-messaging-sw.js')
                  // navigator.serviceWorker.register(BASE_URL_NO_INDEX+'assets/helper/firebase-messaging-sw.js').then(registration => {
                  //     firebase.messaging().useServiceWorker(registration)
                  // })
                  navigator.serviceWorker.register('firebase-messaging-sw.js', {
                      scope:BASE_URL_NO_INDEX
                  }).then(function(registration) {
                      console.log('Registration successful, scope is:', registration.scope);
                  }).catch(function(err) {
                      console.log('Service worker registration failed, error:', err);
                  });
              }
              // console.log(configFcm)
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

                  var data = payload.data;
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
                      "tapToDismiss": false,
                      "onclick":function(res){
                          // if(payload_data != null && payload_data.notif_menu_id != null && payload_data.notif_menu_id != undefined ){
                          //     $("#tombol-ke-menu").data('con',payload_data.notif_menu_id).click();
                          // }
                      },
                  };

                  if(data.user_type == '0'){
                      HELPER.getTotalNotif();
                      if(typeof loadTableInbox !== 'undefined'){
                          loadTableInbox()
                      }
                  }

                  data = $.extend(true,{
                      is_unseen: "true",
                      type: "info"
                  },data);

                  if (data.type == "success") {
                      toastr.success(data.notif_message, data.notif_title);
                  }else if(data.type == "error"){
                      toastr.error(data.notif_message, data.notif_title);
                  }else if(data.type == "warning"){
                      toastr.warning(data.notif_message, data.notif_title);
                  }else if(data.type == "info"){
                      toastr.info(data.notif_message, data.notif_title);
                  }else if (data.type == 'logout') {
                      FCM.getToken({
                          callback: function (hasil) {
                              if (hasil.success) {
                                  FCM.deleteToken({
                                      callback: function (result) {
                                          if (result.success) {
                                              HELPER.logout();
                                          }else{
                                              HELPER.showMessage({'message': result.message});
                                          }
                                      }
                                  })
                              }else{
                                  HELPER.logout();
                              }
                          }
                      });
                  }else{
                      toastr.info(data.notif_message, data.notif_title);
                  }
                  
              });
          }
      },
  }
}();
