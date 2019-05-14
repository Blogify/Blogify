<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<meta name="_token" content="{{ csrf_token() }}" />
	<title>Blogify</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>

	<link rel="stylesheet" href="{{ asset("assets/blogify/assets/stylesheets/styles.css") }}" />
	<link rel="stylesheet" href="{{ asset("assets/blogify/assets/stylesheets/custom.css") }}" />
</head>
<body>
	@yield('body')

	<script src="/assets/blogify/assets/scripts/frontend.js" type="text/javascript"></script>
	@yield('scripts')
	<script src="/assets/blogify/assets/scripts/admin/custom-v-2.js" type="text/javascript"></script>
</body>
</html>