<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Angaar</title>
  <link href="assets/styles/base.css" type="text/css" rel="stylesheet">
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
    }
  </style>
</head>
<body>

  <table>
    <tr>
      <th>Lisaja</th>
      <th>Hääli</th>
      <th>Kommentaare</th>
      <th>Idee</th>
      <th>Kirjeldus</th>
      <th>Valdkond</th>
      <th>Vastutaja</th>
    </tr>
    <?php foreach ( $ideas as $idea ): ?>
      <tr data-idea-id="<?= $idea->id ?>">
        <td><?= $idea->user->name ?></td>
        <td><?= count($idea->votes) ?></td>
        <td><?= count($idea->comments) ?></td>
        <td><?= link_to("http://eos.crebit.ee/angaar/#ideas/{$idea->id}", $idea->title) ?></td>
        <td><?= $idea->description ?></td>
        <td><input type="text" name="area" value="<?= $idea->area ?>" placeholder="Valdkond"/></td>
        <td><input type="text" name="responsible" value="<?= $idea->responsible ?>" placeholder="Vastutaja"/></td>
      </tr>
    <?php endforeach ?>
  </table>

  <!-- Dependencies -->
  <script src="assets/scripts/jquery/jquery-1.10.2.min.js"></script>

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
    });
  </script>
</body>
</html>
