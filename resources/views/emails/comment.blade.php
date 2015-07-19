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
						<td width="100%" style="font-size:20pt;font-weight:300;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';" align="center" height="1">
							Brainstorm
						</td>
					</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" style="line-height:25px" border="0" align="center">
								<tbody>
								<tr>
									<td colspan="3" height="30"></td>
								</tr>
								<tr>
									<td width="36"></td>
									<td width="454" style="color:#444444;border-collapse:collapse;font-size:11pt;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';max-width:454px" valign="top">
										<?php if ( App::getLocale() == 'et' ): ?>
											{{ $comment->user->name }} lisas kommentaari sinu ideele <a href="{{ $comment->idea->generateURL() }}" target="_blank" style="color:#f60;">"{{ $comment->idea->title }}"</a>:
										<?php else: ?>
											{{ $comment->user->name }} added a new comment to your idea <a href="{{ $comment->idea->generateURL() }}" target="_blank" style="color:#f60;">"{{ $comment->idea->title }}"</a>:
										<?php endif ?>
										<br><br>
										<blockquote style="border-left:4px solid #CCC;margin:0;padding-left:10px;color:#777;font-style:italic;white-space:pre-line;">{{ $comment->text }}</blockquote>
									</td>
									<td width="36"></td>
								</tr>
								<tr>
									<td width="36"></td>
									<td width="454" style="color:#777;border-collapse:collapse;font-size:10pt;font-family:'Open Sans','Segoe UI',Arial,'Sans Serif';max-width:454px" valign="top">
										<br><br>
										<?php if ( App::getLocale() == 'et' ): ?>
											Aitäh <a href="{{ env('APP_URL') }}" target="_blank" style="color:#f60;">ideekeskkonda</a> kasutamast!<br>
											&ndash; Angaari meeskond
										<?php else: ?>
											Thank You for using <a href="{{ env('APP_URL') }}" target="_blank" style="color:#f60;">Brainstorm</a>!<br>
											&ndash; The Brainstorm Team
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