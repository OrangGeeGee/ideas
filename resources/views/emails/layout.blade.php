<html>
<head>
<style type="text/css">
  body {
    font: 11pt/12pt Open Sans, Segoe UI, Arial, sans-serif;
    color: #444;
    border: 1px #ddd solid;
    padding: 20px 25px;
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
    font-size: 10pt;
    color: #777;
    font-style: italic;
  }

  .idea-description {
    font-size: 10pt;
  }

  .idea {
    padding: 20px 0;
    border-bottom: 2px solid #CCC;
  }

  .action-icon {
    font-size: 20px;
    display: inline-block;
    vertical-align: middle;
  }

  .action-description {
    font-size:9pt;
    color:#777;
  }

  #footer {
    margin-top: 40px;
    font-size: 10pt;
    color: #777;
  }
</style>
</head>
<body>
  <h1>{{ $title }}</h1>
  @yield('content')

  <div id="footer">
    {!! View::make('emails.footer', [ 'locale' => $locale ]) !!}
  </div>
</body>
</html>