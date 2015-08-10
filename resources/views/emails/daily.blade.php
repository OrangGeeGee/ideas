<center>
	<table cellpadding="8" cellspacing="0" style="padding:0;width:100%!important;background:#ffffff;margin:0;background-color:#ffffff" border="0">
		<tbody>
		<tr>
			<td valign="top">
				<table cellpadding="0" cellspacing="0" style="border-radius:10px;border:1px #ddd solid" border="0" align="center">
					<tr>
						<td colspan="3" height="20"></td>
					</tr>
					<tr>
						<td width="100%" style="font-size:20pt;font-weight:300;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';" align="center">
							{{ $title }}
						</td>
					</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" style="line-height:25px" border="0" align="center">
								<tbody>
								<tr>
									<td colspan="3" height="30"></td>
								</tr>

								<?php if ( $ideas->count() ): ?>
									<tr>
										<td width="36"></td>
										<td width="454" style="color:#444444;border-collapse:collapse;font-size:11pt;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';max-width:454px" valign="top">
											<?php foreach ( $ideas as $idea ): ?>
												{{ $idea->user->name }} <span style="font-size:9pt;color:#777;">{{ trans('emails.addedIdea') }}</span> <a href="{{ $idea->generateURL() }}" target="_blank" style="color:#f60;font-size:10pt;">{{ $idea->title }}</a>
												<hr/>
											<?php endforeach ?>
										</td>
										<td width="36"></td>
									</tr>
								<?php endif ?>

								<?php if ( $comments->count() ): ?>
									<tr>
										<td width="36"></td>
										<td width="454" style="color:#444444;border-collapse:collapse;font-size:11pt;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';max-width:454px" valign="top">
											<?php foreach ( $comments as $comment ): ?>
												<span style="line-height:12pt;">{{ $comment->user->name }} <span style="font-size:9pt;color:#777;">{{ trans('emails.commentedOn') }}</span> <a href="{{ $comment->idea->generateURL() }}" target="_blank" style="color:#f60;font-size:10pt;">{{ $comment->idea->title }}</a></span>
												<blockquote style="border-left:4px solid #CCC;padding:5px 0 5px 15px;margin:10px 0 20px 10px;color:#777;font-size:10pt;line-height:12pt;white-space:pre-line;">{{ $comment->text }}</blockquote>
												<hr/>
											<?php endforeach ?>
										</td>
										<td width="36"></td>
									</tr>
								<?php endif ?>

								<?php if ( $votes->count() ): ?>
									<tr>
										<td width="36"></td>
										<td width="454" style="color:#444444;border-collapse:collapse;font-size:11pt;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';max-width:454px" valign="top">
											<?php foreach ( $votes as $vote ): ?>
												{{ $vote->user->name }} <span style="font-size:9pt;color:#777;">{{ trans('emails.votedFor') }}</span> <a href="{{ $vote->idea->generateURL() }}" target="_blank" style="color:#f60;font-size:10pt;">{{ $vote->idea->title }}</a>
												<hr/>
											<?php endforeach ?>
										</td>
										<td width="36"></td>
									</tr>
								<?php endif ?>

								<tr>
									<td width="36"></td>
									<td width="454" style="color:#777;border-collapse:collapse;font-size:10pt;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';max-width:454px" valign="top">
										<br>
										<?php if ( App::getLocale() == 'et' ): ?>
											Aitäh <a href="{{ env('APP_URL') }}" target="_blank" style="color:#f60;">ideekeskkonda</a> kasutamast!<br>
											&ndash; Angaari meeskond
										<?php else: ?>
											Thank You for using <a href="{{ env('APP_URL') }}" target="_blank" style="color:#f60;">{{ trans('app.name') }}</a>!<br>
											&ndash; The {{ trans('app.name') }} Team
										<?php endif ?>
									</td>
									<td width="36"></td>
								</tr>
								<tr>
									<td colspan="3" height="36"></td>
								</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		</tbody>
	</table>
</center>