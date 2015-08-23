@extends('emails.layout')

@section('content')
	<p>{!! localize('emails.likedYourComment', $locale, [
		'user' => $like->user->name,
		'idea' => Email::generateIdeaLink($like->comment->idea)
	]) !!}:</p>
	<blockquote>{{ $like->comment->text }}</blockquote>
@endsection