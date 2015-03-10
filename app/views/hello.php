<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Brainstorming</title>
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300" type="text/css" rel="stylesheet">
  <link href="assets/styles/base.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/layout.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/headings.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/idea-form.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/ideas-list.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/entry-list.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/comments.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/users.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/filters.css" type="text/css" rel="stylesheet">
  <link href="assets/plugins/modals/modals.css" type="text/css" rel="stylesheet">
</head>
<body>

  <div id="filter-section">
    <input type="text" id="searchField" placeholder="Search..."/>
    <ul id="categories-list"></ul>
    <ul id="sorting-options-list"></ul>
  </div>

  <!-- Templates -->
  <script type="text/html" id="idea-form-template">
    <h2><input type="text" name="title" placeholder="Idea title"/></h2>
    <select name="category_id">
    <?php foreach ( Category::all() as $category ): ?>
      <option value="<?= $category->id ?>"><?= $category->name ?></option>
    <?php endforeach ?>
    </select>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="submit" value="Add"/>
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

  <script type="text/html" id="new-idea-template">
    <img src="<?= url('/assets/images/pencil-icon.png') ?>"/>
    <h3>Add new idea</h3>
  </script>

  <!-- Dependencies -->
  <script src="assets/scripts/jquery/jquery-1.10.2.min.js"></script>
  <script src="assets/scripts/jquery/jquery.helpers.js"></script>
  <script src="assets/scripts/moment/moment-2.4.0.min.js"></script>
  <script src="assets/scripts/backbone/underscore-1.5.2.js"></script>
  <script src="assets/scripts/backbone/backbone-1.1.0.js"></script>
  <script src="assets/scripts/backbone/model.js"></script>
  <script src="assets/scripts/backbone/collection.js"></script>
  <script src="assets/scripts/helpers/array.js"></script>
  <script src="assets/scripts/helpers/string.js"></script>
  <script src="assets/plugins/modals/modals.js"></script>
  <script src="assets/plugins/viewport.js"></script>

  <!-- App files -->
  <script>
    USER_PROFILE_IMAGE_PATH = 'https://workspaces.swedbank.net/project/IDpicture/intranet/';
    USER_ID = '<?= Auth::user()->id ?>';
  </script>
  <script src="assets/scripts/routes.js"></script>
  <script src="assets/scripts/users.js"></script>
  <script src="assets/scripts/ideas.js"></script>
  <script src="assets/scripts/comments.js"></script>
  <script src="assets/scripts/categories.js"></script>
  <script src="assets/scripts/sorting.js"></script>
</body>
</html>
