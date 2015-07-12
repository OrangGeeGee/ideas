<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Angaar</title>
  <link type="text/css" rel="stylesheet" href="styles/fonts.css">
  <link type="text/css" rel="stylesheet" href="styles/base.css">
  <link type="text/css" rel="stylesheet" href="styles/layout.css">
  <link type="text/css" rel="stylesheet" href="styles/headings.css">
  <link type="text/css" rel="stylesheet" href="styles/buttons.css">
  <link type="text/css" rel="stylesheet" href="styles/idea-form.css">
  <link type="text/css" rel="stylesheet" href="styles/ideas-list.css">
  <link type="text/css" rel="stylesheet" href="styles/entry-list.css">
  <link type="text/css" rel="stylesheet" href="styles/comments.css">
  <link type="text/css" rel="stylesheet" href="styles/users.css">
  <link type="text/css" rel="stylesheet" href="styles/filters.css">
  <link type="text/css" rel="stylesheet" href="plugins/modals/modals.css">
  <link type="text/css" rel="stylesheet" href="plugins/introjs/introjs.css">
  <style type="text/css">
    .in-progress .entry-content h3:before {
      content: "<?= trans('statuses.inProgress') ?>";
    }

    .finished .entry-content h3:before {
      content: "<?= trans('statuses.done') ?>";
    }
  </style>
</head>
<body>

  <a href="//ee.swedbank.net/" style=""><?= trans('frame.backToIntranet') ?></a>

  <div id="header">
    <div id="profile-section" style="display: none;">

    </div>
    <div id="filter-section">
      <input type="text" id="searchField" placeholder="<?= trans('frame.searchPlaceholder') ?>"/>
      <ul id="categories-list"></ul>
      <ul id="sorting-options-list"></ul>
    </div>
  </div>

  <!-- Templates -->
  <script type="text/html" id="idea-form-template">
    <h2><input type="text" name="title" placeholder="<?= trans('ideas.titlePlaceholder') ?>"/></h2>
    <select name="category_id">
    <?php foreach ( App\Category::all() as $category ): ?>
      <option value="<?= $category->id ?>"><?= $category->name ?></option>
    <?php endforeach ?>
    </select>
    <textarea name="description" placeholder="<?= trans('ideas.descriptionPlaceholder') ?>"></textarea>
    <input type="submit" value="<?= trans('ideas.submit') ?>"/>
  </script>

  <script type="text/html" id="event-form-template">
    <h2><input type="text" name="title" placeholder="<?= trans('events.titlePlaceholder') ?>"/></h2>
    <input type="text" name="location" placeholder="<?= trans('events.locationPlaceholder') ?>"/>
    <textarea name="description" placeholder="<?= trans('events.descriptionPlaceholder') ?>"></textarea>
    <input type="text" name="expectedPersonCount" placeholder="<?= trans('events.expectedPersonCountPlaceholder') ?>"/>
    <input type="text" name="date" placeholder="<?= trans('events.datePlaceholder') ?>"/>
    <input type="submit" value="<?= trans('events.create') ?>"/>
  </script>

  <script type="text/html" id="user-header-template">
    <h2><?= trans('frame.hi') ?>, <%= name.getForename() %></h2>
    <p><?= trans('frame.introduction') ?></p>
  </script>

  <script type="text/html" id="comment-form-template">
    <div class="entry-author">
      <%= Users.get(USER_ID).generateProfileImage() %>
    </div>
    <div class="entry-content">
      <textarea name="text" placeholder="<?= trans('comments.placeholder') ?>"></textarea>
      <input type="submit" value="<?= trans('comments.add') ?>"/>
    </div>
  </script>

  <script type="text/html" id="idea-template">
    <div class="entry-author">
      <%= user.generateProfileImage() %>
      <h4><%= user.get('name') %></h4>
    </div>
    <div class="entry-content" title="<?= trans('comments.open') ?>">
      <h3><%= title %></h3>
      <p><%= description %></p>
      <div class="content-fader"></div>
    </div>
    <ul class="entry-data">
      <li class="comments">
        <% if ( comments.length == 1 ) { %><a href="#">1 <?= trans('comments.one') ?></a><% } %>
        <% if ( comments.length > 1 ) { %><a href="#"><%= comments.length %> <?= trans('comments.many') ?></a><% } %>
        <% if ( !comments.length ) { %><?= trans('comments.missing') ?><% } %>
      </li>
      <?php if ( Auth::user()->hasEstonianEmailAddress() ): ?>
      <% if ( user_id == USER_ID || events.length ) { %>
      <li class="event">
        <% if ( events.length > 0 ) { %>
          <?= trans('nextEventAt') ?> <%= moment(events[0].get('date')).format('Do MMMM HH:mm') %>
        <% } else if ( user_id == USER_ID ) { %>
          <a href="ideas/<%= id %>/event"><?= trans('events.add') ?></a>
        <% } %>
      </li>
      <% } %>
      <?php endif ?>
      <% if ( user_id == USER_ID ) { %>
      <li class="delete">
        <a href="ideas/<%= id %>/delete"><?= trans('ideas.delete') ?></a>
      </li>
      <% } %>
      <% if ( user_id != USER_ID && !isFinished ) { %>
        <% if ( hasBeenVotedFor ) { %>
          <li class="vote">
            <?= trans('voted') ?> – <a href="ideas/<%= id %>/unvote"><?= trans('ideas.removeVote') ?></a>
          </li>
        <% } else { %>
          <li class="vote">
            <a href="ideas/<%= id %>/vote"><?= trans('ideas.vote') ?></a>
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

  <script type="text/html" id="event-template">
    <div class="entry-author">
      <%= user.generateProfileImage() %>
      <h4><%= user.get('name') %> created an event</h4>
    </div>
    <div class="entry-content">
      <blockquote>
        <p><%= description %></p>
        <a href="<%= generateShadowEnvironmentLink() %>" target="_blank">View the event in Shadowing environment</a>
      </blockquote>
    </div>
  </script>

  <script type="text/html" id="new-idea-template">
    <img src="<?= url('/images/pencil-icon.png') ?>"/>
    <h3><?= trans('ideas.add') ?></h3>
  </script>

  <!-- Dependencies -->
  <script src="scripts/jquery/jquery-1.10.2.min.js"></script>
  <script src="scripts/jquery/jquery.helpers.js"></script>
  <script src="scripts/moment/moment-2.4.0.min.js"></script>
  <?php if ( Config::get('language') == 'EST' ): ?>
  <script src="scripts/moment/moment.et.js"></script>
  <?php endif ?>
  <script src="scripts/backbone/underscore-1.5.2.js"></script>
  <script src="scripts/backbone/backbone-1.1.0.js"></script>
  <script src="scripts/backbone/model.js"></script>
  <script src="scripts/backbone/collection.js"></script>
  <script src="scripts/helpers/browser.js"></script>
  <script src="scripts/helpers/function.js"></script>
  <script src="scripts/helpers/array.js"></script>
  <script src="scripts/helpers/string.js"></script>
  <script src="plugins/modals/modals.js"></script>
  <script src="plugins/introjs/intro.js"></script>
  <script src="plugins/viewport.js"></script>

  <!-- App files -->
  <script>
    USER_PROFILE_IMAGE_PATH = 'https://workspaces.swedbank.net/project/IDpicture/intranet/';
    USER_ID = '<?= Auth::user()->id ?>';

    /**
     * @param {String} path
     * @return {String}
     */
    function generateShadowEnvironmentLink(path) {
      var domain = isProduction()
        ? 'http://eos.crebit.ee/shadow/'
        : 'http://localhost:85/';

      return domain + (path || '');
    }

    /**
     * @return {boolean}
     */
    function isProduction() {
      return '<?= App::environment() ?>' == 'production';
    }
  </script>
  <script src="scripts/routes.js"></script>
  <script src="scripts/users.js"></script>
  <script src="scripts/ideas.js"></script>
  <script src="scripts/comments.js"></script>
  <script src="scripts/events.js"></script>
  <script src="scripts/categories.js"></script>
  <script src="scripts/sorting.js"></script>
  <script>
    SortingOptions.add([
      { id: 1, name: '<?= trans('sorting.byDate') ?>' },
      { id: 2, name: '<?= trans('sorting.byPopularity') ?>' }
    ]);
  </script>
  <script src="scripts/timestamps.js"></script>
  <script src="scripts/tutorial.js"></script>
</body>
</html>
