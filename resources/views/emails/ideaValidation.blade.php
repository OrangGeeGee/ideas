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
              {{ trans('app.name') }}
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
                    {{ $idea->user->name }} lisas Ideekeskkonda uue idee:
                    <br><br>
                    <a href="{{ $idea->generateURL() }}" target="_blank" style="color:#f60;font-weight:300;font-size:14pt;">{{ $idea->title }}</a>
                    <br><br>
                    Kui idee tundub valiidne, anna sellest sekretäridele märku ja nad viivad idee esitajale kommi.
                    <a href="{{ env('APP_URL') }}ideas/{{ $idea->id }}/notifySecretaries" target="_blank" style="color:#f60;font-weight:300;">Candy time!</a>
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