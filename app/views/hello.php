<?php
require 'Labels.php';
Labels::initialize();
?>
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
  <link href="assets/styles/ideas-list.css?20150416b" type="text/css" rel="stylesheet">
  <link href="assets/styles/entry-list.css?20150416b" type="text/css" rel="stylesheet">
  <link href="assets/styles/comments.css?20150416b" type="text/css" rel="stylesheet">
  <link href="assets/styles/users.css" type="text/css" rel="stylesheet">
  <link href="assets/styles/filters.css" type="text/css" rel="stylesheet">
  <link href="assets/plugins/modals/modals.css" type="text/css" rel="stylesheet">
  <link href="assets/plugins/introjs/introjs.css" type="text/css" rel="stylesheet">
  <style type="text/css">
    .in-progress .entry-content h3:before {
      content: "<?= Labels::get('inProgress') ?>";
    }

    .finished .entry-content h3:before {
      content: "<?= Labels::get('done') ?>";
    }
  </style>
</head>
<body>

  <a href="//ee.swedbank.net/" style=""><?= Labels::get('toIntranet') ?></a>

  <div id="header">
    <div id="profile-section" style="display: none;">

    </div>
    <div id="filter-section">
      <input type="text" id="searchField" placeholder="<?= Labels::get('searchPlaceholder') ?>"/>
      <ul id="categories-list"></ul>
      <ul id="sorting-options-list"></ul>
    </div>
  </div>

  <!-- Templates -->
  <script type="text/html" id="idea-form-template">
    <h2><input type="text" name="title" placeholder="<?= Labels::get('ideaTitlePlaceholder') ?>"/></h2>
    <select name="category_id">
    <?php foreach ( Category::all() as $category ): ?>
      <option value="<?= $category->id ?>"><?= $category->name ?></option>
    <?php endforeach ?>
    </select>
    <textarea name="description" placeholder="<?= Labels::get('ideaDescriptionPlaceholder') ?>"></textarea>
    <input type="submit" value="<?= Labels::get('add') ?>"/>
  </script>

  <script type="text/html" id="user-header-template">
    <h2><?= Labels::get('hi') ?>, <%= name.getForename() %></h2>
    <p><?= Labels::get('introduction') ?></p>
  </script>

  <script type="text/html" id="comment-form-template">
    <div class="entry-author">
      <%= Users.get(USER_ID).generateProfileImage() %>
    </div>
    <div class="entry-content">
      <textarea name="text" placeholder="<?= Labels::get('commentPlaceholder') ?>"></textarea>
      <input type="submit" value="<?= Labels::get('addComment') ?>"/>
    </div>
  </script>

  <script type="text/html" id="idea-template">
    <div class="entry-author">
      <%= user.generateProfileImage() %>
      <h4><%= user.get('name') %></h4>
    </div>
    <div class="entry-content" title="<?= Labels::get('openComments') ?>">
      <h3><%= title %></h3>
      <p><%= description %></p>
      <div class="content-fader"></div>
    </div>
    <ul class="entry-data">
      <li class="comments">
        <% if ( comments.length == 1 ) { %><a href="#">1 <?= Labels::get('comment') ?></a><% } %>
        <% if ( comments.length > 1 ) { %><a href="#"><%= comments.length %> <?= Labels::get('comments') ?></a><% } %>
        <% if ( !comments.length ) { %><?= Labels::get('noComments') ?><% } %>
      </li>
      <% if ( user_id == USER_ID ) { %>
      <li class="delete">
        <a href="ideas/<%= id %>/delete" title="Kustuta oma idee"><?= Labels::get('delete') ?></a>
      </li>
      <% } %>
      <% if ( user_id != USER_ID && !isFinished ) { %>
        <% if ( hasBeenVotedFor ) { %>
          <li class="vote">
            <?= Labels::get('voted') ?> – <a href="ideas/<%= id %>/unvote"><?= Labels::get('removeVote') ?></a>
          </li>
        <% } else if ( user.hasFreeVotes() ) { %>
          <li class="vote">
            <a href="ideas/<%= id %>/vote"><?= Labels::get('vote') ?></a>
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
    <h3><?= Labels::get('addNewIdea') ?></h3>
  </script>

  <!-- Dependencies -->
  <script src="assets/scripts/jquery/jquery-1.10.2.min.js"></script>
  <script src="assets/scripts/jquery/jquery.helpers.js"></script>
  <script src="assets/scripts/moment/moment-2.4.0.min.js"></script>
  <?php if ( Config::get('language') == 'EST' ): ?>
  <script src="assets/scripts/moment/moment.et.js"></script>
  <?php endif ?>
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
  <script>
    SortingOptions.add([
      { id: 1, name: '<?= Labels::get('sortingByDate') ?>' },
      { id: 2, name: '<?= Labels::get('sortingByPopularity') ?>' }
    ]);
  </script>
  <script src="assets/scripts/timestamps.js"></script>
  <script src="assets/scripts/tutorial.js"></script>
</body>
</html>
