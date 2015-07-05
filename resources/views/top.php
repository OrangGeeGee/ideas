<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Angaar</title>
  <link type="text/css" rel="stylesheet" href="styles/base.css">
  <style type="text/css">
    table {
      border-spacing: 0;
    }
    th,
    td {
      padding: 3px;
    }
    th {
      text-align: left;
    }
    td:first-child {
      width: 100px;
    }
    th:nth-child(4),
    td:nth-child(4) {
      width: 20%
    }
    td {
      border-top: 1px solid #CCC;
      white-space: pre-line;
    }
    .status {
      padding: 2px 5px;
      color: white;
    }
    tr[data-status="1"] .status {
      background-color: #df7df7;
    }
    tr[data-status="2"] .status {
      background-color: orange;
    }
    tr[data-status="2"] .status:before {
      content: "✔ ";
    }
    tr.filled td {
      background-color: white;
    }
  </style>
</head>
<body>

  <table>
    <tr>
      <th>Staatus</th>
      <th>Lisaja</th>
      <th>Hääli</th>
      <th>Kommentaare</th>
      <th>Idee</th>
      <th>Kirjeldus</th>
      <th>Valdkond</th>
      <th>Vastutaja</th>
    </tr>
    <?php foreach ( $ideas as $idea ): ?>
      <tr data-idea-id="<?= $idea->id ?>" data-status="<?= $idea->status_id ?>" class="<?= $idea->area || $idea->responsible ? 'filled' : '' ?>">
        <td>
          <span class="status"><?= $idea->status ? $idea->status->name : '' ?></span>
        </td>
        <td><?= $idea->user->name ?></td>
        <td><?= $idea->votes->count() ?></td>
        <td><?= $idea->comments->count() ?></td>
        <td><a href="<?= url("/#ideas/{$idea->id}") ?>" target="_blank"><?= $idea->title ?></a></td>
        <td><?= $idea->description ?></td>
        <td><input type="text" name="area" value="<?= $idea->area ?>" placeholder="Valdkond"/></td>
        <td><input type="text" name="responsible" value="<?= $idea->responsible ?>" placeholder="Vastutaja"/></td>
      </tr>
    <?php endforeach ?>
  </table>

  <!-- Dependencies -->
  <script src="scripts/jquery/jquery-1.10.2.min.js"></script>

  <!-- App files -->
  <script>
    $('table').on('change', 'input', function() {
      var $row = $(this).closest('tr[data-idea-id]');
      var params = {
        id: $row.data('ideaId'),
        area: $row.find('[name="area"]').val(),
        responsible: $row.find('[name="responsible"]').val()
      };

      $.post('<?= url('top/update') ?>', params);

      $row.toggleClass('filled', !!params.area || !!params.responsible);
    });
  </script>
</body>
</html>
