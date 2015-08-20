@extends('emails.layout')

@section('content')
	<?php if ( $ideas->count() ): ?>
		<ul class="daily-list">
			<?php foreach ( $ideas as $idea ): ?>
				<li>
					{{ $idea->user->name }}
					<span class="daily-action">{{ trans('emails.addedIdea') }}</span>
					{!! Email::generateIdeaLink($idea) !!}
				</li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if ( $comments->count() ): ?>
		<ul class="daily-list">
			<?php foreach ( $comments as $comment ): ?>
				<?php if ( $comment->idea ): ?>
					<li>
						{{ $comment->user->name }}
						<span class="daily-action">{{ trans('emails.commentedOn') }}</span>
						{!! Email::generateIdeaLink($comment->idea) !!}
						<blockquote>{{ $comment->text }}</blockquote>
					</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<?php if ( $votes->count() ): ?>
		<ul class="daily-list">
			<?php foreach ( $votes as $vote ): ?>
				<?php if ( $vote->idea ): ?>
					<li>
						{{ $vote->user->name }}
						<span class="daily-action">{{ trans('emails.votedFor') }}</span>
						{!! Email::generateIdeaLink($vote->idea) !!}
					</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
@endsection