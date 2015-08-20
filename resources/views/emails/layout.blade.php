<style type="text/css">
  #container {
    border: 1px #ddd solid;
    border-radius: 10px;
    font: 11pt 'Open Sans', 'Segoe UI', Arial, sans-serif;
    color: #444;
    padding: 40px;
  }

  h1 {
    font-size: 20pt;
    font-weight: 300;
    text-align: center;
    margin-top: -10px;
  }

  h2 {
    font-weight: 300;
    font-size: 14pt;
  }

  a {
    color: #F60;
  }

  blockquote {
    border-left: 4px solid #CCC;
    margin-left: 5px;
    padding-left: 10px;
    color: #777;
    font-style: italic;
    white-space: pre-line;
  }

  .daily-list {
    line-height:12pt;
  }

  .daily-list > li {
    margin-bottom: 25px;
  }

  .daily-action {
    font-size:9pt;
    color:#777;
  }

  #footer {
    margin-top: 40px;
  }
</style>

<div id="container">
  <h1>{{ $title }}</h1>
  @yield('content')

  <div id="footer">
    {!! View::make('emails.footer', [ 'locale' => $locale ]) !!}
  </div>
</div>