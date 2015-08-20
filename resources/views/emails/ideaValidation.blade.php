@extends('emails.layout')

@section('content')
  <p>{{ $idea->user->name }} lisas Ideekeskkonda uue idee:</p>
  <h2>{!! Email::generateIdeaLink($idea) !!}</h2>

  <p>Kui idee tundub valiidne, anna sellest sekretäridele märku ja nad viivad idee esitajale kommi. <a href="{{ env('APP_URL') }}ideas/{{ $idea->id }}/notifySecretaries" target="_blank">Candy time!</a></p>
@endsection