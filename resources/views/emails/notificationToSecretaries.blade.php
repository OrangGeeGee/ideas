@extends('emails.layout')

@section('content')
  <p>{{ $idea->user->name }} lisas Ideekeskkonda uue idee:</p>
  <h2>{!! Email::generateIdeaLink($idea) !!}</h2>

  <p>Kui {{ $idea->user->getFirstName() }} töötab sinu korrusel, siis ole hea ja vii talle miskit magusat. :) Kui sul on magus otsas (või Mattias ei ole sulle magusat toonudki), siis anna sellest <a href="mailto:mattias.saldre@swedbank.ee">Mattiasele</a> märku.</p>
@endsection