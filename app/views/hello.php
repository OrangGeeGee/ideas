<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Brainstorming</title>
  <link href="assets/styles/base.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/layout.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/headings.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/idea-form.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/ideas-list.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/entry-list.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/comments.css" type="text/css" rel="stylesheet">
  <link href="assets/plugins/modals/modals.css" type="text/css" rel="stylesheet">
</head>
<body>

  <!-- Templates -->
  <script type="text/html" id="idea-form-template">
    <input type="text" name="title" placeholder="Idee pealkiri"/>
    <textarea name="description" placeholder="Kirjeldus"></textarea>
    <input type="submit" value="Lisa idee"/>
  </script>

  <script type="text/html" id="comment-form-template">
    <textarea name="text" placeholder="Kommentaar"></textarea>
    <input type="submit" value="Lisa kommentaar"/>
  </script>

  <script type="text/html" id="idea-template">
    <div class="entry-author">
      <img src="<%= USER_PROFILE_IMAGE_PATH + user.id %>.jpg"/>
      <h4><%= user.name.getForename() %></h4>
      <%= moment(created_at).fromNow() %>
    </div>
    <div class="entry-content" title="Ava kommentaarid">
      <h3><%= title %></h3>
      <p><%= description %></p>
      <ul class="entry-data">
        <li class="comments"><%= comments.length || 'No' %> comments</li>
      </ul>
    </div>
  </script>

  <script type="text/html" id="comment-template">
    <div class="entry-author">
      <img src="<%= USER_PROFILE_IMAGE_PATH + user.id %>.jpg"/>
      <h4><%= user.name.getForename() %></h4>
      <%= moment(created_at).fromNow() %>
    </div>
    <div class="entry-content">
      <p><%= text %></p>
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
  <script src="assets/scripts/helpers/array.js"></script>
  <script src="assets/scripts/helpers/string.js"></script>
  <script src="assets/plugins/modals/modals.js"></script>
  <script src="assets/plugins/viewport.js"></script>

  <!-- App files -->
  <script>USER_PROFILE_IMAGE_PATH = 'https://workspaces.swedbank.net/project/IDpicture/intranet/';</script>
  <script src="assets/scripts/users.js"></script>
  <script src="assets/scripts/ideas.js"></script>
  <script src="assets/scripts/comments.js"></script>
</body>
</html>
