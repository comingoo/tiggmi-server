<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="utf-8">
</head>

<body>
	<h4>Dear {!! $user->name !!} ,</h4>

	<h2> Welcome on {{ config('app.name') }} .</h2>

	<div>
		Your One Time Password is <br/>
		{{!! $user->OTP !!}}	<br/>
	</div>
	<div>
		We are sure you will enjoy using this service.
		<br/> Please don't hesitate to send us your feedback or comments.
	</div>
	
	<div>We wish you a pleasant experience using our portal.
		<br/>Regards
	</div>

</body>

</html>