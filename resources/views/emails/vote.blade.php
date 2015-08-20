@extends('emails.layout')

@section('content')
	<p>{{ localize('emails.votedForYourIdea', $locale, [ 'user' => $vote->user->name ]) }}</p>
	<h2>{!! Email::generateIdeaLink($vote->idea) !!}</h2>
@endsection