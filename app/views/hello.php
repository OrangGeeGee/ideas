<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Angaar</title>
  <link href="assets/styles/fonts.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/base.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/layout.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/headings.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/buttons.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/idea-form.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/ideas-list.css?20150416" type="text/css" rel="stylesheet">
  <link href="assets/styles/entry-list.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/comments.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/users.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/filters.css" type="text/css" rel="stylesheet">
  <link href="assets/plugins/modals/modals.css" type="text/css" rel="stylesheet">
  <link href="assets/plugins/introjs/introjs.css" type="text/css" rel="stylesheet">
</head>
<body>

  <a href="//ee.swedbank.net/" style="">Tagasi intranetti</a>

  <div id="header">
    <div id="profile-section" style="display: none;">

    </div>
    <div id="filter-section">
      <input type="text" id="searchField" placeholder="Otsi..."/>
      <ul id="categories-list"></ul>
      <ul id="sorting-options-list"></ul>
    </div>
  </div>

  <!-- Templates -->
  <script type="text/html" id="idea-form-template">
    <h2><input type="text" name="title" placeholder="Idee pealkiri"/></h2>
    <select name="category_id">
    <?php foreach ( Category::all() as $category ): ?>
      <option value="<?= $category->id ?>"><?= $category->name ?></option>
    <?php endforeach ?>
    </select>
    <textarea name="description" placeholder="Kirjeldus"></textarea>
    <input type="submit" value="Lisa"/>
  </script>

  <script type="text/html" id="user-header-template">
    <h2>Hei, <%= name.getForename() %></h2>
    <p>Angaari-raames saad esitada ja kommenteerida ideid ning hääletada parimate poolt 15. aprillini.</p>
  </script>

  <script type="text/html" id="comment-form-template">
    <div class="entry-author">
      <%= Users.get(USER_ID).generateProfileImage() %>
    </div>
    <div class="entry-content">
      <textarea name="text" placeholder="Kommentaar"></textarea>
      <input type="submit" value="Lisa kommentaar"/>
    </div>
  </script>

  <script type="text/html" id="idea-template">
    <div class="entry-author">
      <%= user.generateProfileImage() %>
      <h4><%= user.get('name') %></h4>
    </div>
    <div class="entry-content" title="Ava kommentaarid">
      <h3><%= title %></h3>
      <p><%= description %></p>
      <div class="content-fader"></div>
    </div>
    <ul class="entry-data">
      <li class="comments">
        <% if ( comments.length == 1 ) { %><a href="#" title="Ava kommentaar">1 kommentaar</a><% } %>
        <% if ( comments.length > 1 ) { %><a href="#" title="Ava kommentaarid"><%= comments.length %> kommentaari</a><% } %>
        <% if ( !comments.length ) { %>Kommentaarid puuduvad<% } %>
      </li>
      <% if ( user_id == USER_ID ) { %>
      <li class="delete">
        <a href="ideas/<%= id %>/delete" title="Kustuta oma idee">Kustuta</a>
      </li>
      <% } %>
      <% if ( user_id != USER_ID && !isFinished ) { %>
        <% if ( hasBeenVotedFor ) { %>
          <li class="vote">
            Hääletatud – <a href="ideas/<%= id %>/unvote">Kustuta hääl</a>
          </li>
        <% } else if ( user.hasFreeVotes() ) { %>
          <li class="vote">
            <a href="ideas/<%= id %>/vote">Anna hääl</a>
          </li>
        <% } %>
      <% } %>
    </ul>
  </script>

  <script type="text/html" id="comment-template">
    <div class="entry-author">
      <%= user.generateProfileImage() %>
      <h4><%= user.get('name') %></h4>
    </div>
    <div class="entry-content">
      <p><%= text %></p>
    </div>
  </script>

  <script type="text/html" id="new-idea-template">
    <img src="<?= url('/assets/images/pencil-icon.png') ?>"/>
    <h3>Lisa uus idee</h3>
  </script>

  <!-- Dependencies -->
  <script src="assets/scripts/jquery/jquery-1.10.2.min.js"></script>
  <script src="assets/scripts/jquery/jquery.helpers.js"></script>
  <script src="assets/scripts/moment/moment-2.4.0.min.js"></script>
  <script src="assets/scripts/moment/moment.et.js"></script>
  <script src="assets/scripts/backbone/underscore-1.5.2.js"></script>
  <script src="assets/scripts/backbone/backbone-1.1.0.js"></script>
  <script src="assets/scripts/backbone/model.js"></script>
  <script src="assets/scripts/backbone/collection.js"></script>
  <script src="assets/scripts/helpers/browser.js"></script>
  <script src="assets/scripts/helpers/function.js"></script>
  <script src="assets/scripts/helpers/array.js"></script>
  <script src="assets/scripts/helpers/string.js"></script>
  <script src="assets/plugins/modals/modals.js"></script>
  <script src="assets/plugins/introjs/intro.js"></script>
  <script src="assets/plugins/viewport.js"></script>

  <!-- App files -->
  <script>
    USER_PROFILE_IMAGE_PATH = 'https://workspaces.swedbank.net/project/IDpicture/intranet/';
    USER_ID = '<?= Auth::user()->id ?>';

    /**
     * @return {boolean}
     */
    function isProduction() {
      return '<?= App::environment() ?>' == 'production';
    }
  </script>
  <script src="assets/scripts/routes.js"></script>
  <script src="assets/scripts/users.js"></script>
  <script src="assets/scripts/ideas.js?20150416"></script>
  <script src="assets/scripts/comments.js"></script>
  <script src="assets/scripts/categories.js"></script>
  <script src="assets/scripts/sorting.js"></script>
  <script src="assets/scripts/timestamps.js"></script>
  <script src="assets/scripts/tutorial.js"></script>
</body>
</html>
