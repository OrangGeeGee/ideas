@extends('emails.layout')

@section('content')
	<p>{!! localize('emails.addedComment', $locale, [
		'user' => $comment->user->name,
		'idea' => Email::generateIdeaLink($comment->idea)
	]) !!}</p>
	<blockquote>{{ $comment->text }}</blockquote>
@endsection