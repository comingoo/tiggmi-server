<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="utf-8">
</head>

<body>
	<h4>Dear {!! $user->first_name !!} {!! $user->last_name !!},</h4>

	<h2> A request was made to reset your password for the {{ config('app.name') }} login. </h2>

	<div>
		In order to avoid misuse and ensure security, we do not reset the password before confirming that the request came from the
		owner of the account.
	</div>
	<br/>
	<div>
		Please confirm by clicking
		<a href="{{ URL::to(env('APP_WEB_URL').'/forgot-password/'. $token) }}">here</a> to reset your password. This confirmation link will be active for next 7 days, after that you need to make a new
		password change request.
	</div>
	<br/>
	<div>
		If the above link does not work, please copy and paste the link below on your browser's address bar:
		<br/>
		<a href="{{ URL::to(env('APP_WEB_URL').'/forgot-password/'. $token) }}">{{ URL::to(env('APP_WEB_URL').'/forgot-password/'. $token) }}</a>
	</div>
	<br/>
	<div>
		<h5>Important Information:</h5>
		Anyone knowing your username can request password change, but only you will receive this email for confirming it. If you
		do not wish to change the password, you can ignore this mail. Be assured that only you have access to your account and
		information in it, and nobody else could change the password and get access to your account.
	</div>
	<br/>
	<div>We wish you a pleasant experience using our portal.
		<br/>Regards
	</div>

</body>

</html>