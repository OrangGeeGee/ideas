<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Brainstorming</title>
  <link href="assets/styles/base.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/headings.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/idea-form.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/ideas-list.css" type="text/css" rel="stylesheet">
</head>
<body>

  <!-- Templates -->
  <script type="text/html" id="idea-form-template">
    <input type="text" name="title" placeholder="Idee pealkiri"/>
    <textarea name="description" placeholder="Kirjeldus"></textarea>
    <input type="submit" value="Lisa idee"/>
  </script>

  <script type="text/html" id="idea-template">
    <div class="idea-author">
      <img/>
      <%= moment(created_at).fromNow() %>
    </div>
    <div class="idea-content">
      <h3><%= title %></h3>
      <p><%= description %></p>
    </div>
  </script>

  <!-- Dependencies -->
  <script src="assets/scripts/jquery/jquery-1.10.2.min.js"></script>
  <script src="assets/scripts/jquery/jquery.helpers.js"></script>
  <script src="assets/scripts/moment/moment-2.4.0.min.js"></script>
  <script src="assets/scripts/moment/moment.et.js"></script>
  <script src="assets/scripts/backbone/underscore-1.5.2.js"></script>
  <script src="assets/scripts/backbone/backbone-1.1.0.js"></script>
  <script src="assets/scripts/backbone/collection.js"></script>

  <!-- App files -->
  <script src="assets/scripts/ideas.js"></script>
</body>
</html>
