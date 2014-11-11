<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Brainstorm</h2>
	  <p>{{ $ideaAuthor->name }} just commented on your idea "{{ $comment->idea->title }}":</p>
	  <blockquote>{{ $comment->text }}</blockquote>
	</body>
</html>