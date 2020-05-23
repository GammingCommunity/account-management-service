<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Global chat</title>
</head>

<body>

	<style>
		.message{
			padding: 6px;
			border-radius: 5px;
		}
	</style>

	@foreach ($chattings as $chatting)
	<p class="message">{{ $chatting->content }}</p>
	@endforeach

</body>

</html>