
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      ff_login();
    } else {
      // The person is not logged into your app or we are unable to tell.
      console.log('Please log into this app.');
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
      appId      : '959514004246468',
      xfbml      : true,
      version    : 'v3.0'
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
//    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
	function ff_login()
	{
		alert("@#");
         FB.login(function (response) {
				
             if (response.authResponse) {
					
                 console.log('Welcome!  Fetching your information.... ');
                 FB.api('/me?fields=id,name,email', function (response) {

						var sns_key = response.id;
						var email = response.email;
						var name = response.name;	
						var gubun = "facebook";

						var mode = document.getElementById("mode").value;
						alert("sns_key="+sns_key+"&mode="+mode);
						$.ajax({
							url: "/member/sns_facebook_login.php",
							type: "POST",
							data: "sns_key="+sns_key+"&mode="+mode,
							error : function(request, status, error) {
							 //통신 에러 발생시 처리
								alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
								$("#ajax_loader").addClass("display-none");
							}
							,complete: function(request, status, error) {
								
				//				$("#ajax_loader").addClass("display-none");
							}
							, success : function(response, status, request) {
								alert("@!#");
								
							    //회원가입 프로세스 절차 
								if(mode == "false"){								
									if (response.trim() == "2")
									{
										alert("이미 가입된 회원입니다.");
										location.href="/login/login.php";								
									}else{																		
										document.getElementById("sns_key").value = sns_key;
										document.getElementById("user_name").value = name;
										document.getElementById("userEmail").value = email;
										document.getElementById("gubun").value = 'facebook';
										var form=document.loginForm;
										form.action="./member_form.php";
										form.submit();									
									}
								}else{
									//페이스북 로그인 접근 시 
									if(response.trim() == "2"){
										location.href="/";
									}else{

										document.getElementById("sns_key").value = sns_key;
										document.getElementById("user_name").value = name;
										document.getElementById("userEmail").value = email;
										document.getElementById("gubun").value = 'facebook';
										var form=document.loginForm;
										form.action="/member/member_form.php";
										form.submit();										
									}

								}else{
									alert(response);
								}
							}
						});
                 });


             } else {
					
                 //alert('User canceled login or did not fully authorize the app.');

             }

         }, {
			
             scope: 'public_profile,email' // we don't need any scopes in demo...
         });
	}
	
</script>

