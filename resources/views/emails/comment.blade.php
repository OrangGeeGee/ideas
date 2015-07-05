<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Angaar</h2>
	  <p>{{ $user->name }} lisas kommentaari sinu ideele <a href="http://eos.crebit.ee/angaar/#ideas/{{ $comment->idea->id }}" target="_blank">"{{ $comment->idea->title }}"</a>:</p>
	  <blockquote>{{ $comment->text }}</blockquote>
	</body>
</html>