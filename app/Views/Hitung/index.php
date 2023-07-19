<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  ini adalah halaman hitung
  <table border="1">
    <thead>
      <tr>
        <th>Pemasukkan</th>
        <th>Total Makanan Terjual</th>
        <th>Rata rata</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $row) : ?>
        <tr>
          <td><?= $row['pemasukkan'] ?></td>
          <td><?= $row['total_makanan_terjual'] ?></td>
          <td><?= $row['rata_rata'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>