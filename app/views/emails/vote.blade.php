<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
    <h2>Angaar</h2>
    <p>{{ $user->name }} andis oma hääle sinu ideele <a href="http://eos.crebit.ee/brainstorm/#ideas/{{ $idea->id }}" target="_blank">"{{ $idea->title }}"</a>.</p>
	</body>
</html>