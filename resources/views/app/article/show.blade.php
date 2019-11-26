<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="viewport" content="target-densitydpi=device-dpi, width=480px, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes"> 
		<title>{{$article->title}}</title>
		<style type="text/css">
		h1{
			width: 100%;
			height: 100%;
		}
		a{
			text-decoration: none;
			color:#05c1f1;
		}
		h6{
		   display: inline;
		   color: darkgray;
		}
		img{
			width: 100%;
			height: 100%;
		}
		</style>
	</head>
	<body>
		<h1>{{$article->title}}</h1>
		{{--<a href="https://www.baidu.com/">百度一下</a>--}}
		<h6>{{$article->created_at}}</h6>

		{!! $article->content !!}
	</body>
</html>
