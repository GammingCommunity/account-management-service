<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Global chat</title>
</head>

<body>

	@foreach ($chattings as $chatting)
	<p class="message">{{ $chatting->content }}</p>
	@endforeach

</body>

</html>