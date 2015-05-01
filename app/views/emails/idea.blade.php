<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Angaar</h2>
    <p>{{ $user->name }} lisas Ideekeskkonda uue idee <a href="http://eos.crebit.ee/angaar/#ideas/{{ $idea->id }}" target="_blank">"{{ $idea->title }}"</a>.</p>
  </body>
</html>