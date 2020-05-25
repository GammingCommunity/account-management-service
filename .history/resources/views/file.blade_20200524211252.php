<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>File Management</title>
</head>

<body>

	<style>
	</style>

	<ul class="file-list">
		@foreach ($fileNames as $fileName)
		<li>
			<a href="{{ $fileDir }}{{ $fileName }}"></a>
		</li>
		@endforeach
	</ul>

	<form action="/chat" method="post" class="chat-box-container">
		{{ csrf_field() }}
		<textarea id="chat-box" name="content"></textarea>
		<button type="submit">➤</button>
	</form>

</body>

</html>