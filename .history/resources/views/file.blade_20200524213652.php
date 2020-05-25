<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>File Management</title>
</head>

<body>

	<style>
		button {
			padding: 6px 10px;
			border: none;
			color: white;
			cursor: pointer;
			border-radius: 9px;
			background-color: rgb(0, 153, 255);
		}
	</style>

	<ul class="file-list">
		@foreach ($fileNames as $fileName)
		<li>
			<a href="/{{ $fileDir }}{{ $fileName }}">{{ $fileName }}</a>
		</li>
		@endforeach
	</ul>

	<form action="/file" method="post">
		{{ csrf_field() }}
		<input type="file" name="uploadingFile" required>
		<button type="submit">Upload ➤</button>
	</form>

</body>

</html>