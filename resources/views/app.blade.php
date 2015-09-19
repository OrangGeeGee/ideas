<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= trans('app.name') ?></title>
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
  <link type="text/css" rel="stylesheet" href="styles/settings.css">
  <link type="text/css" rel="stylesheet" href="plugins/modals/modals.css">
  <link type="text/css" rel="stylesheet" href="plugins/layers/layers.css">
  <link type="text/css" rel="stylesheet" href="plugins/introjs/introjs.css">
  <link type="text/css" rel="stylesheet" href="plugins/atjs/jquery.atwho.css">
  <style type="text/css">
    <?php foreach ( App\Status::all() as $status ): ?>
      #ideas-list > li[data-status="{{ $status->code }}"] .entry-content h3:before {
        content: "<?= trans('statuses.' . camel_case($status->code)) ?>";
      }
    <?php endforeach ?>

    <?php foreach ( App\Category::all() as $category ): ?>
      body[data-active-category="{{ $category->id }}"] #ideas-list > li[data-category-id="{{ $category->id }}"] {
        display: block;
      }
    <?php endforeach ?>

    #new-idea .cc-counter:after {
      content: " {!! trans('ideas.charactersLeft') !!}";
    }

    .view-count:after {
      content: " {!! trans_choice('ideas.views', 2) !!}";
    }

    [data-view-count="1"] .view-count:after {
      content: " {!! trans_choice('ideas.views', 1) !!}";
    }
  </style>
  <!--[if IE 8]>
  <script src="scripts/html5shiv/html5shiv-3.7.3.js"></script>
  <link type="text/css" rel="stylesheet" href="styles/ie8.css">
  <![endif]-->
</head>
<body>

  <div id="container">
    <a href="//ee.swedbank.net/" style=""><?= trans('frame.backToIntranet') ?></a>

    <header>
      <div id="userBar">
        <span id="userName" title="{{ trans('settings.hint') }}"><?= Auth::user()->getFirstName() ?></span>
        <h2><?= trans('app.name') ?></h2>

        <ul id="onlineUsersList" class="hidden" title="{{ trans('frame.currentlyOnline') }}"></ul>
      </div>

      <div id="filter-section">

        {{ trans('filters.heading') }}
        <input type="text" id="searchField" placeholder="<?= trans('filters.searchPlaceholder') ?>"/>
        <select id="category">
          <?php foreach ( App\Category::all() as $category ): ?>
            <option value="{{ $category->id }}">{{ trans('categories.category'.$category->id) }}</option>
          <?php endforeach ?>
        </select>
        <select id="sorting">
          <?php foreach ( [trans('filters.byDate'), trans('filters.byPopularity')] as $index => $sortingMethod ): ?>
            <option value="{{ $index + 1 }}">{{ $sortingMethod }}</option>
          <?php endforeach ?>
        </select>
      </div>
    </header>

    <section id="activitySection"></section>
  </div>

  <!-- Templates -->
  <script type="text/html" id="landingModalTemplate">
    <?= View::make('landing-' . App::getLocale()) ?>
  </script>

  <script type="text/html" id="layerTemplate">
    <div class="layer">
      <div class="layer-inner" style="background-image: linear-gradient(rgba(255, 119, 0, 0.5), orange 50%), url(<?= App\WHOISUser::find(Auth::user()->id)->profileImageURL ?>);">
        <div class="layer-body"></div>
      </div>
    </div>
  </script>

  <script type="text/html" id="userSettingsTemplate">
    <h2><?= trans('settings.title') ?></h2>
    <dl class="settings-list">
      <div>
        <dt>
          <label for="receiveVoteNotification">{{ trans('settings.receiveVoteNotification') }}</label>
        <p>{{ trans('settings.receiveVoteNotification.description') }}</p>
        </dt>
        <dd>
          <input type="checkbox" id="receiveVoteNotification" name="receiveVoteNotification" <%= receiveVoteNotification ? ' checked' : '' %>/>
        </dd>
      </div>

      <div>
        <dt>
          <label for="receiveCommentNotification">{{ trans('settings.receiveCommentNotification') }}</label>
        <p>{{ trans('settings.receiveCommentNotification.description') }}</p>
        </dt>
        <dd>
          <input type="checkbox" id="receiveCommentNotification" name="receiveCommentNotification" <%= receiveCommentNotification ? ' checked' : '' %>/>
        </dd>
      </div>

      <div>
        <dt>
          <label for="receiveCommentLikeNotification">{{ trans('settings.receiveCommentLikeNotification') }}</label>
        <p>{{ trans('settings.receiveCommentLikeNotification.description') }}</p>
        </dt>
        <dd>
          <input type="checkbox" id="receiveCommentLikeNotification" name="receiveCommentLikeNotification" <%= receiveCommentLikeNotification ? ' checked' : '' %>/>
        </dd>
      </div>

      <div>
        <dt>
          <label for="receiveMentionNotification">{{ trans('settings.receiveMentionNotification') }}</label>
        <p>{{ trans('settings.receiveMentionNotification.description') }}</p>
        </dt>
        <dd>
          <input type="checkbox" id="receiveMentionNotification" name="receiveMentionNotification" <%= receiveMentionNotification ? ' checked' : '' %>/>
        </dd>
      </div>

      <div>
        <dt>
          <label for="receiveDailyNewsletter">{{ trans('settings.receiveDailyNewsletter') }}</label>
          <p>{{ trans('settings.receiveDailyNewsletter.description') }}</p>
        </dt>
        <dd>
          <input type="checkbox" id="receiveDailyNewsletter" name="receiveDailyNewsletter" <%= receiveDailyNewsletter ? ' checked' : '' %>/>
        </dd>
      </div>
    </dl>

    <button><?= trans('settings.save') ?></button>
  </script>

  <script type="text/html" id="ideaFormTemplate">
    <h2><input type="text" name="title" placeholder="<?= trans('ideas.titlePlaceholder') ?>" maxlength="70" autocomplete="off"/></h2>
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

  <script type="text/html" id="commentFormTemplate">
    <textarea name="text" placeholder="<?= trans('comments.placeholder') ?>"></textarea>
    <p class="hint">{{ trans('comments.mentionHint') }}</p>

    <% if ( attributes.user_id == USER_ID || Users.get(USER_ID).get('settings').canModerateStatuses ) { %>
      <div class="status-container">
        {{ trans('statuses.change') }}
        <select name="status_id">
          <?php foreach ( App\Status::orderBy('position')->get() as $status ): ?>
            <option value="{{ $status->id }}">{{ trans('statuses.' . camel_case($status->code)) }}</option>
          <?php endforeach ?>
        </select>
      </div>
    <% } %>

    <div class="upload-button-container">
      <label>
        <input type="file" name="image"/>
        <img src="images/cloud-with-up-arrow.png"/>
        <span data-original-text="Upload image">Upload image</span>
      </label>
    </div>

    <input type="submit" value="<?= trans('comments.add') ?>"/>
  </script>

  <script type="text/html" id="ideaModalTemplate">
    <section class="idea-description-section">
      <form class="idea-form">
        <% if ( attributes.user_id == USER_ID ) { %>
          <img src="images/edit.png" title="{{ trans('ideas.edit') }}" class="edit-action"/>
        <% } %>

        <h2 class="idea-title"><%= attributes.title %></h2>
        <input type="text" name="title" value="<%= attributes.title %>"/>

        <p class="idea-description"><%= attributes.description %></p>
        <textarea name="description"><%= attributes.description %></textarea>

        <button>{{ trans('ideas.saveChanges') }}</button>

        <% if ( attributes.user_id != USER_ID && !isFinished() ) { %>
          <a class="vote-action" href="ideas/<%= id %>/vote">
            <span class="text"><?= trans('ideas.vote') ?></span>
            <img class="thumbs-up" src="images/thumbs-up.png"/>
            <img class="loading-icon" src="images/loading-animation-2.gif">
          </a>
        <% } %>
      </form>
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
          <img class="thumbs-up" src="images/thumbs-up.png"/>
          <img class="loading-icon" src="images/loading-animation-2.gif">
        </a>
      <% } %>

      <ul class="entry-data">
        <li class="votes">
          <img src="images/thumbs-up-black.png"/>
          <span class="vote-count"></span> <?= trans('ideas.peopleLike') ?>
        </li>
        <li class="sharing">
          <a href="#">
            <img src="images/share.png"/>
            <?= trans('ideas.share') ?>
          </a>
        </li>
        <li class="comments">
          <img src="images/comments.png"/>
          <% if ( comments.length == 1 ) { %><a href="#">1 <?= trans('comments.one') ?></a><% } %>
          <% if ( comments.length > 1 ) { %><a href="#"><%= comments.length %> <?= trans('comments.many') ?></a><% } %>
          <% if ( !comments.length ) { %><?= trans('comments.missing') ?><% } %>
        </li>
        <li class="views">
          <img src="images/eye.png"/>
          <span class="view-count"></span>
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
        <% if ( user_id != USER_ID ) { %>
          <li class="subscription">
            <img src="images/subscription.png"/>
            <% if ( hasBeenSubscribedTo ) { %>
              <?= trans('ideas.subscribed') ?> <a href="ideas/<%= id %>/subscribe" class="unsubscribe"><?= trans('ideas.unsubscribe') ?></a>
            <% } else { %>
              <a href="ideas/<%= id %>/subscribe" class="subscribe" title="<?= trans('ideas.subscribeHint') ?>"><?= trans('ideas.subscribe') ?></a>
            <% } %>
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

      <footer class="comment-footer">
        <% if ( user_id != USER_ID && !isLiked ) { %>
          <a href="#" class="comment-vote-action">{{ trans('comments.like') }}</a>
        <% } %>

        <% if ( isLiked && likes.length == 1 ) { %>
          <span class="like-count"><%= localize('comments.youLikeThis') %></span>
        <% } else if ( isLiked && likes.length > 1 ) { %>
          <span class="like-count"><%= localize('comments.youAndOtherPeopleLikeThis', { likes: likes.length - 1 }) %></span>
        <% } else if ( likes.length > 0 ) { %>
          <span class="like-count"><%= localize('comments.peopleLikeThis', { likes: likes.length }) %></span>
        <% } %>
      </footer>
    </div>
  </script>

  <script type="text/html" id="commentAttachmentTemplate">
    <a href="uploads/<%= id %>" target="_blank">
      <img src="uploads/<%= id %>"/>
    </a>
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

  <script type="text/html" id="activityListIdeaTemplate">
    <div class="entry-author">
      <%= Users.get(attributes.user_id).generateProfileImage() %>
      <span class="user-name"><%= Users.get(attributes.user_id).get('name') %></span>
      <span class="action">{{ trans('ideas.added') }}</span>
    </div>
    <div class="entry-content">
      <%= generateLink() %>
    </div>
  </script>

  <script type="text/html" id="activityListCommentTemplate">
    <div class="entry-author">
      <%= Users.get(attributes.user_id).generateProfileImage() %>
      <span class="user-name"><%= Users.get(attributes.user_id).get('name') %></span>
    </div>
    <div class="entry-content">
      <p><%= attributes.text %></p>
      <%= Ideas.get(attributes.idea_id).generateLink() %>
    </div>
  </script>

  <!-- Dependencies -->
  <script src="scripts/helpers/object.js"></script>
  <script src="scripts/helpers/array.js"></script>
  <script src="scripts/helpers/string.js"></script>
  <script src="scripts/helpers.js"></script>
  <script src="scripts/jquery/jquery-1.10.2.min.js"></script>
  <script src="scripts/jquery/jquery.helpers.js"></script>
  <script src="scripts/moment/moment-2.4.0.min.js"></script>
  <?php if ( Config::get('language') == 'EST' ): ?>
  <script src="scripts/moment/moment.et.js"></script>
  <?php endif ?>
  <script src="scripts/backbone/underscore-1.5.2.js"></script>
  <script src="scripts/backbone/backbone-1.1.0.js"></script>
  <script src="scripts/backbone/model.js"></script>
  <script src="scripts/linkify/linkify.js"></script>
  <script src="scripts/linkify/linkify-jquery.js"></script>
  <script src="plugins/atjs/jquery.caret.js"></script>
  <script src="plugins/atjs/jquery.atwho.js"></script>
  <script src="plugins/layers/layers.js"></script>
  <script src="scripts/helpers/layout.js"></script>
  <script src="scripts/helpers/browser.js"></script>
  <script src="scripts/helpers/function.js"></script>
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
  <script src="scripts/landing.views.js"></script>
  <script src="scripts/routes.js"></script>
  <script src="scripts/users.js"></script>
  <script src="scripts/user.views.js"></script>
  <script src="scripts/settings.js"></script>
  <script src="scripts/setting.views.js"></script>
  <script src="scripts/ideas.js"></script>
  <script src="scripts/idea.views.js"></script>
  <script src="scripts/idea.subscriptions.js"></script>
  <script src="scripts/ideaViews.js"></script>
  <script src="scripts/votes.js?20150820"></script>
  <script src="scripts/comments.js?20150820"></script>
  <script src="scripts/comment.views.js"></script>
  <script src="scripts/commentLikes.js"></script>
  <?php if ( env('SHADOW_URL') ): ?>
  <script src="scripts/events.js?20150820"></script>
  <?php endif ?>
  <script src="scripts/event.views.js"></script>
  <script src="scripts/activities.js"></script>
  <script src="scripts/activity.views.js"></script>
  <script src="scripts/statuses.js?20150820"></script>
  <?php if ( env('POLLING_INTERVAL') ): ?>
  <script>POLLING_INTERVAL = <?= env('POLLING_INTERVAL') ?>;</script>
  <script src="scripts/dataPoller.js"></script>
  <?php endif ?>
  <script src="scripts/timestamps.js"></script>
  <script src="scripts/tutorial.js"></script>
  <script src="scripts/filtering.views.js"></script>

  <!-- Client side localization -->
  <script src="scripts/localization.js"></script>
  <script>
    localize('removeVoteConfirmation', '{!! trans('ideas.removeVoteConfirmation') !!}');
    localize('deleteConfirmation', '{!! trans('ideas.deleteConfirmation') !!}');
    localize('askRecipientEmail', '{!! trans('ideas.askRecipientEmail') !!}');
    localize('thanksForSharing', '{!! trans('ideas.thanksForSharing') !!}');
    localize('comments.like', '{!! trans('comments.like') !!}');
    localize('comments.youLikeThis', '{!! trans('comments.youLikeThis') !!}');
    localize('comments.peopleLikeThis', '{!! trans('comments.peopleLikeThis') !!}');
    localize('comments.youAndOtherPeopleLikeThis', '{!! trans('comments.youAndOtherPeopleLikeThis') !!}');
    localize('ideas.view', '{!! trans_choice('ideas.views', 1) !!}');
    localize('ideas.views', '{!! trans_choice('ideas.views', 2) !!}');
    localize('ideas.unsubscribeConfirmation', '{!! trans('ideas.unsubscribeConfirmation') !!}');
    localize('images.fileExtensionNotAllowed', '{!! trans('images.fileExtensionNotAllowed') !!}');
    localize('images.sizeWarning', '{!! trans('images.sizeWarning') !!}');
  </script>

  <!-- Initial data -->
  <script>
    Ideas.add(<?= \App\Idea::all() ?>);
    IdeaViews.add(<?= \App\View::all() ?>);
    Votes.add(<?= \App\Vote::all() ?>);
    Comments.add(<?= \App\Comment::with('images')->get() ?>);
    CommentLikes.add(<?= \App\CommentLike::all() ?>);
    Users.add(<?= $users ?>);
    Settings.set(<?= \Auth::user()->settings ?>);
    Statuses.add(<?= $statuses ?>);
    StatusChanges.add(<?= \App\StatusChange::all() ?>);
    Subscriptions.add(<?= \App\IdeaSubscription::all() ?>);
  </script>

  <!-- Boot process -->
  <script src="scripts/boot.js"></script>
</body>
</html>
