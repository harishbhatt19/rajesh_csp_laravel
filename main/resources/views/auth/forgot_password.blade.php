<!DOCTYPE html>
<html>
<head>
	<title> Forgot Password </title>
	<link rel="shortcut icon" href="http://franchise.finigoo.com/app-assets/img/fevi.png" /> 
	<link rel="stylesheet" href="{{ asset('assets/css/bootoast.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets_login/css/style.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
 
	<img class="wave" src="{{ asset('assets_login/img/bg3.png') }}">
	<div class="container">
		<div class="img">
			<img src="{{ asset('assets_login/img/bg.svg') }}">
		</div>

		<div class="login-content login_section">
      
			<form >
			    @csrf
				<img src="{{ asset('assets_login/img/avatar.svg') }}">
				<h2 class="title">Welcome</h2>

                <p>Enter phone</p>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fa fa-user fa-2x"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Phone</h5>
           		   		<input  class="input" name="phone" id="phone" type="text" maxlength="10" 
           		   		onkeypress="return isNumber(event)"  autocomplete="off" autofocus value="{{ old('email') }}" required>
           		   </div>
           		</div>
           		
           		<div class="otp-section" style="display:none;">
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fa fa-user fa-2x"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>OTP</h5>
           		   		<input  class="input" name="otp" id="otp" type="text" maxlength="6" 
           		   		onkeypress="return isNumber(event)"  >
           		   </div>
           		</div>
           		</div>
           		
           		<div class="div">
       		   		<span class="error phone-error"></span>
       		    </div>
       		   
            	<input type="button" class="btn send-btn"  value="Send">
            	<input type="button" class="btn verify-btn"  value="Verify" style="display:none;">
				
            </form>
			
            </div>
            
            
        <div class="login-content password_section"  style="display:none;">
      
			<form >
			    @csrf
				<img src="{{ asset('assets_login/img/avatar.svg') }}">
				<h2 class="title">New Password</h2>
				
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fa fa-user fa-2x"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Password</h5>
           		   		<input  class="input" name="password" id="password" type="password" value="">
           		   </div>
           		</div>
           		
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fa fa-user fa-2x"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Confirm Password</h5>
           		   		<input  class="input" name="confirm_password" id="confirm_password" type="password" >
           		   </div>
           		</div>
           		
           		
           		<div class="div">
       		   		<span class="error password-error"></span>
       		    </div>
       		   
            	<input type="button" class="btn password-btn"  value="Reset Password">
            	
				
            </form>
			
            </div>    
        
        
        </div>
   
    
    <script type="text/javascript" src="{{ asset('assets_login/js/main.js') }}"></script>
    
    <script type="text/javascript">

    $(document).on('click', '.send-btn', function () {
        var phone = $('#phone').val();
        $('.phone-error').html();
        if(phone){
            $.ajax({
                type: 'post',
                url: '{{ route("post:send_forgot_otp") }}',
                data: {"phone" : phone,"_token":"{{ csrf_token() }}"},
                success: function (result) {
                    if(result.success) {
                        $('#otp').show();
                        $('#phone').attr('disabled','true')
                        $('.send-btn').hide();
                        $('.verify-btn').show();
                        $('.otp-section').show();
                    }else{
                        $('.phone-error').html(result.message);
                    }
                }
            })
        }
    });


    $(document).on('click', '.verify-btn', function () {
        var phone = $('#phone').val();
        var otp = $('#otp').val();
        if(otp){
            $.ajax({
                type: 'post',
                url: '{{ route("post:verify_forgot_otp") }}',
                data: {"phone" : phone,"otp" : otp,"_token":"{{ csrf_token() }}"},
                success: function (result) {
                    if(result.success) {
                        $('.password_section').show();
                        $('.login_section').hide();
                    }else{
                        $('.phone-error').html(result.message);
                    }
                }
            })
        }
    });

    $(document).on('click', '.password-btn', function () {
        var phone = $('#phone').val();
        var otp = $('#otp').val();
        var password = $('#password').val();
        
        var confirm_password = $('#confirm_password').val();
        
        if(password == confirm_password) {
            $('.phone-error').html('Password does not match');
        }
        
        if(password && confirm_password){
            $.ajax({
                type: 'post',
                url: '{{ route("post:update_password") }}',
                data: {"phone" : phone,"otp" : otp,'password':password, "_token":"{{ csrf_token() }}"},
                success: function (result) {
                    if(result.success) {
                        window.location.replace("https://merchant.finigoo.com/");
                    }else{
                        $('.password-error').html(result.message);
                    }
                }
            })
        }
        else{
            showMyToast("error","Enter password"); 
        }
    });






    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function showMyToast($type , $message) {
        bootoast.toast({ message: $message, type: $type, position: 'right-top' });
    }
    @if (Session::has('success'))
        showMyToast("success","{{ Session::get('success') }}"); 
    @endif
    @if (Session::has('error'))
        showMyToast("error","{{ Session::get('error') }}"); 
    @endif
    </script>
    
    <script src="{{ asset('assets/js/bootoast.js') }}"></script>
    @yield('customjs')
</body>
</html>