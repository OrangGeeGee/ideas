@extends('emails.layout')

@section('content')
	<?php if ( $ideas->count() ): ?>
		<table class="ideas-list">
			<?php foreach ( $ideas as $index => $idea ): ?>
				<tr>
					<td class="idea">
						<?php if ( substr($idea->created_at, 0, 10) >= $periodStart ): ?>
							{{ $idea->user->name }}
							<span class="action-description">{{ localize('emails.addedIdea', $locale) }}</span>
							{!! Email::generateIdeaLink($idea) !!}

							<p class="idea-description">{!! nl2br($idea->description) !!}</p>
						<?php else: ?>
							<p>{!! Email::generateIdeaLink($idea) !!}</p>
						<?php endif ?>

						<?php
							$votes = $idea->votes->filter(function($vote) use($periodStart) {
								return $vote->timestamp > $periodStart;
							});

							if ( $votes->count() ):
						?>
							<p>
								<span class="action-icon">&#10084;</span>
								{{ humanizeList(App\WHOISUser::whereIn('id', $votes->lists('user_id'))->get()->lists('name'), $locale) }}
								<span class="action-description">{{ trans_choice('emails.voted', $votes->count(), [], null, $locale) }}</span>
							</p>
						<?php endif ?>

						<?php
							$comments = $idea->comments->filter(function($comment) use($periodStart) {
								return $comment->created_at > $periodStart;
							});

							if ( $comments->count() ):
						?>
							<table class="comments-list">
								<?php foreach ( $comments as $comment ): ?>
									<tr>
										<td>
											<span class="action-icon">&#10077;</span> {{ isset($comment->user) ? $comment->user->name : $comment->user_id }}
											<span class="action-description">{{ localize('emails.commentedOn', $locale) }}:</span>
										</td>
									</tr>
									<tr>
										<td>
											<blockquote>{!! nl2br($comment->text) !!}</blockquote>
										</td>
									</tr>
								<?php endforeach ?>
							</table>
						<?php endif ?>
					</td>
				</tr>
			<?php endforeach ?>
		</table>
	<?php endif ?>
@endsection