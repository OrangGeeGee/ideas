@extends('emails.layout')

@section('content')
	<p>{!! localize('emails.sharingDescription', $locale, [
		'sharer' => $sharer->name,
		'idea' => Email::generateIdeaLink($idea)
	]) !!}</p>
	<blockquote>{!! nl2br($idea->description) !!}</blockquote>
@endsection