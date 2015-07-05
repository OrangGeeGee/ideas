<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Angaar</h2>
    <p>{{ $user->name }} lisas Ideekeskkonda uue idee <a href="http://eos.crebit.ee/angaar/#ideas/{{ $idea->id }}" target="_blank">"{{ $idea->title }}"</a>. Kui {{ $user->getFirstName() }} töötab sinu korrusel, siis ole hea ja vii talle miskit magusat. :) Kui sul on magus otsas (või Mattias ei ole sulle magusat toonudki), siis anna sellest <a href="mailto:mattias.saldre@swedbank.ee">Mattiasele</a> märku.</p>
  </body>
</html>