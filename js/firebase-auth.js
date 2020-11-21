var uiConfig = {
    callbacks: {
        signInSuccessWithAuthResult: function(authResult, redirectUrl) {
            var user = authResult.user;
            var isNewUser = authResult.additionalUserInfo.isNewUser;
            var user_id = user.uid;
            var user_email = user.email;
            if(isNewUser) {
                // Add User Into Database
                var displayName = user.displayName;

                var requestURL = 'http://localhost/web/create_account.php?uid='+user_id+'&name='+displayName+'&email='+user_email;
                var request = new XMLHttpRequest();
	            request.open('GET', requestURL);
	            request.responseType = 'text';
	            request.send();
	
	            request.onload = function() {
                    var resp = request.response;
                    if(resp == "200") {
                        window.location.replace('http://localhost/web/successful_login.php');
                    }
                }
            } else {
                var requestURL = 'http://localhost/web/check_login.php?uid='+user_id;
                var request = new XMLHttpRequest();
	            request.open('GET', requestURL);
	            request.responseType = 'text';
	            request.send();
	
	            request.onload = function() {
                    var resp = request.response;
                    if(resp == "200") {
                        window.location.replace('http://localhost/web/successful_login.php');
                    }
                }
            }
            //return true;
        },
        signInFailure: function(error) {
            // Handle Errors
            return handleUIError(error);
        },
        uiShown: function() {
            document.getElementById('loader').style.display = 'none';
        }
    },
    signInSuccessUrl: 'http://localhost/web/home/',
    signInOptions: [
        firebase.auth.FacebookAuthProvider.PROVIDER_ID,
        firebase.auth.GoogleAuthProvider.PROVIDER_ID,
        firebase.auth.EmailAuthProvider.PROVIDER_ID
    ],
    tosUrl: 'https://',
    privacyPolicyUrl: function() {
       window.location.assign('https://');
    }
};
var ui = new firebaseui.auth.AuthUI(firebase.auth());

ui.start('#firebaseui-auth-container', uiConfig);