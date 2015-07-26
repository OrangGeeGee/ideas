<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Angaar</title>
  <link type="text/css" rel="stylesheet" href="styles/fonts.css">
  <link type="text/css" rel="stylesheet" href="styles/base.css">
  <link type="text/css" rel="stylesheet" href="styles/layout.css">
  <link type="text/css" rel="stylesheet" href="styles/landing.css">
  <link type="text/css" rel="stylesheet" href="styles/headings.css">
  <link type="text/css" rel="stylesheet" href="styles/buttons.css">
  <link type="text/css" rel="stylesheet" href="styles/idea-form.css">
  <link type="text/css" rel="stylesheet" href="styles/ideas-list.css">
  <link type="text/css" rel="stylesheet" href="styles/statuses.css">
  <link type="text/css" rel="stylesheet" href="styles/entry-list.css">
  <link type="text/css" rel="stylesheet" href="styles/comments.css">
  <link type="text/css" rel="stylesheet" href="styles/activities.css">
  <link type="text/css" rel="stylesheet" href="styles/users.css">
  <link type="text/css" rel="stylesheet" href="styles/filters.css">
  <link type="text/css" rel="stylesheet" href="plugins/modals/modals.css">
  <link type="text/css" rel="stylesheet" href="plugins/introjs/introjs.css">
  <style type="text/css">
    <?php foreach ( App\Status::all() as $status ): ?>
      .status-{{ $status->code }} .entry-content h3:before {
        content: "<?= trans('statuses.' . camel_case($status->code)) ?>";
      }
    <?php endforeach ?>
  </style>
</head>
<body>

  <?php if ( !Auth::user()->settings->landingPageVisited ): ?>
    <script src="scripts/landing.js"></script>

    <div id="landingLayer">
      <div id="landingContainer">
        <h1>Ideekeskkond</h1>

        <ul id="featuresList">
          <li>
            <h2>Kes võivad ideid esitada?</h2>
            <p>Kõik, kes unistavad mõnusamast ja kaasaegsemast töökeskkonnast ning uutest ja põnevatest toodetest.</p>
          </li>
          <li>
            <h2>Milliseid ideid võib esitada?</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
          </li>
          <li>
            <h2>Milliseid ideid võib esitada?</h2>
            <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
          </li>
          <li>
            <h2>Milliseid ideid võib esitada?</h2>
            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
          </li>
        </ul>

        <a href="javascript:hideLandingPage();" class="button">{{ trans('landing.startUsing') }}</a>
      </div>
    </div>
  <?php endif ?>

  <div id="container">
    <a href="//ee.swedbank.net/" style=""><?= trans('frame.backToIntranet') ?></a>

    <div id="header">
      <ul id="onlineUsersList"></ul>

      <div id="filter-section">
        <input type="text" id="searchField" placeholder="<?= trans('frame.searchPlaceholder') ?>"/>
        <ul id="categories-list"></ul>
        <ul id="sorting-options-list"></ul>
      </div>
    </div>
  </div>

  <!-- Templates -->
  <script type="text/html" id="ideaFormTemplate">
    <h2><input type="text" name="title" placeholder="<?= trans('ideas.titlePlaceholder') ?>"/></h2>
    <select name="category_id">
    <?php foreach ( App\Category::all() as $category ): ?>
      <option value="<?= $category->id ?>"><?= $category->name ?></option>
    <?php endforeach ?>
    </select>
    <textarea name="description" placeholder="<?= trans('ideas.descriptionPlaceholder') ?>"></textarea>
    <input type="submit" value="<?= trans('ideas.submit') ?>"/>
  </script>

  <script type="text/html" id="eventFormTemplate">
    <h2><input type="text" name="title" placeholder="<?= trans('events.titlePlaceholder') ?>"/></h2>
    <input type="text" name="location" placeholder="<?= trans('events.locationPlaceholder') ?>"/>
    <textarea name="description" placeholder="<?= trans('events.descriptionPlaceholder') ?>"></textarea>
    <input type="text" name="expectedPersonCount" placeholder="<?= trans('events.expectedPersonCountPlaceholder') ?>"/>
    <input type="text" name="date" placeholder="<?= trans('events.datePlaceholder') ?>"/>
    <input type="submit" value="<?= trans('events.create') ?>"/>
  </script>

  <script type="text/html" id="userHeaderTemplate">
    <h2><?= trans('frame.hi') ?>, <%= name.getForename() %></h2>
    <p><?= trans('frame.introduction') ?></p>
  </script>

  <script type="text/html" id="commentFormTemplate">
    <textarea name="text" placeholder="<?= trans('comments.placeholder') ?>"></textarea>
    <% if ( attributes.user_id == USER_ID ) { %>
      <div class="status-container">
        {{ trans('statuses.change') }}
        <select name="status_id">
          <?php foreach ( App\Status::orderBy('position')->get() as $status ): ?>
            <option value="{{ $status->id }}">{{ trans('statuses.' . camel_case($status->code)) }}</option>
          <?php endforeach ?>
        </select>
      </div>
    <% } %>
    <input type="submit" value="<?= trans('comments.add') ?>"/>
  </script>

  <script type="text/html" id="ideaModalTemplate">
    <section class="idea-description-section">
      <h2 class="idea-title"><%= attributes.title %></h2>
      <p class="idea-description"><%= attributes.description %></p>

      <% if ( attributes.user_id != USER_ID && !isFinished() ) { %>
        <a class="vote-action" href="ideas/<%= id %>/vote">
          <span class="text"><?= trans('ideas.vote') ?></span>
          <img src="images/thumbs-up.png"/>
        </a>
      <% } %>
    </section>

    <section class="idea-activity-section">

    </section>
  </script>

  <script type="text/html" id="ideaListItemTemplate">
    <div class="entry-content" title="<?= trans('comments.open') ?>">
      <h3><%= title %></h3>
      <div class="entry-author">
        <%= user.generateProfileImage() %>
        <span class="user-name"><%= user.get('name') %></span>
      </div>
    </div>

    <footer>
      <% if ( user_id != USER_ID && !isFinished ) { %>
        <a class="vote-action" href="ideas/<%= id %>/vote">
          <span class="text"><?= trans('ideas.vote') ?></span>
          <img src="images/thumbs-up.png"/>
        </a>
      <% } %>

      <ul class="entry-data">
        <li class="votes">
          <img src="images/thumbs-up-black.png"/>
          <span class="vote-count"></span> <?= trans('ideas.peopleLike') ?>
        </li>
        <li class="comments">
          <img src="images/comments.png"/>
          <% if ( comments.length == 1 ) { %><a href="#">1 <?= trans('comments.one') ?></a><% } %>
          <% if ( comments.length > 1 ) { %><a href="#"><%= comments.length %> <?= trans('comments.many') ?></a><% } %>
          <% if ( !comments.length ) { %><?= trans('comments.missing') ?><% } %>
        </li>
        <?php if ( Auth::user()->hasEstonianEmailAddress() ): ?>
          <% if ( user_id == USER_ID || events.length ) { %>
            <li class="event">
              <img src="images/calendar.png"/>
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
            <img src="images/delete.png"/>
            <a href="ideas/<%= id %>/delete"><?= trans('ideas.delete') ?></a>
          </li>
        <% } %>
      </ul>
    </footer>
  </script>

  <script type="text/html" id="commentListItemTemplate">
    <div class="entry-author">
      <%= user.generateProfileImage() %>
      <span class="user-name"><%= user.get('name') %></span>
      <% if ( statusChange ) { %>
        <span class="action"><?= trans('statuses.changedTo') ?></span> <%= Statuses.get(statusChange.get('status_id')).get('name') %>
      <% } %>
    </div>
    <div class="entry-content">
      <p><%= text %></p>
    </div>
  </script>

  <script type="text/html" id="eventListItemTemplate">
    <div class="entry-author">
      <%= user.generateProfileImage() %>
      <span class="user-name"><%= user.get('name') %></span> {{ trans('events.created') }}
    </div>
    <div class="entry-content">
      <blockquote>
        <p><%= description %></p>
        <a href="<%= generateShadowEnvironmentLink() %>" target="_blank">{{ trans('events.viewInShadowing') }}</a>
      </blockquote>
    </div>
  </script>

  <script type="text/html" id="newIdeaTemplate">
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
    SHADOW_URL = '<?= env('SHADOW_URL') ?>';

    /**
     * @param {String} path
     * @return {String}
     */
    function generateShadowEnvironmentLink(path) {
      return SHADOW_URL + (path || '');
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
  <script src="scripts/user.views.js"></script>
  <script src="scripts/ideas.js"></script>
  <script src="scripts/idea.views.js"></script>
  <script src="scripts/votes.js"></script>
  <script src="scripts/comments.js"></script>
  <script src="scripts/comment.views.js"></script>
  <?php if ( env('SHADOW_URL') ): ?>
  <script src="scripts/events.js"></script>
  <?php endif ?>
  <script src="scripts/event.views.js"></script>
  <script src="scripts/categories.js"></script>
  <script src="scripts/activity.views.js"></script>
  <script src="scripts/sorting.js"></script>
  <script src="scripts/statuses.js"></script>
  <?php if ( env('POLLING_INTERVAL') ): ?>
  <script>POLLING_INTERVAL = <?= env('POLLING_INTERVAL') ?>;</script>
  <script src="scripts/dataPoller.js"></script>
  <?php endif ?>
  <script>
    SortingOptions.add([
      { id: 1, name: '<?= trans('sorting.byDate') ?>' },
      { id: 2, name: '<?= trans('sorting.byPopularity') ?>' }
    ]);
  </script>
  <script src="scripts/timestamps.js"></script>
  <script src="scripts/tutorial.js"></script>

  <!-- Initial data -->
  <script>
    Categories.add(<?= \App\Category::all() ?>);
    Ideas.add(<?= \App\Idea::all() ?>);
    Votes.add(<?= \App\Vote::all() ?>);
    Comments.add(<?= \App\Comment::all() ?>);
    Users.add(<?= \App\WHOISUser::all() ?>);
    Statuses.add(<?= $statuses ?>);
    StatusChanges.add(<?= \App\StatusChange::all() ?>);
  </script>

  <!-- Boot process -->
  <script src="scripts/boot.js"></script>
</body>
</html>
