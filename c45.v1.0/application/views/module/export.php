<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data History.xls");
?>
<html>
<head>
	<title>Data History</title>
</head>
<body>
	<style type="text/css">
	body{
		font-family: sans-serif;
	}
	table{
		margin: 20px auto;
		border-collapse: collapse;
	}
	table th,
	table td{
		border: 1px solid #3c3c3c;
		padding: 3px 8px;

	}
	a{
		background: blue;
		color: #fff;
		padding: 8px 10px;
		text-decoration: none;
		border-radius: 2px;
	}
	</style>
	<center>
		<h3>Data History</h3>
	</center>
  <table>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Gerakan</th>
      <th>Ekspresi</th>
      <th>Sikap</th>
      <th>Konsen</th>
      <th>Prediksi</th>
      <th>Tanggal Input</th>
    </tr>
    <?php
    $x = 0;
    $data =  $this->db->get('history')->result();
    foreach ($data as $key) {
      $x++;
      ?>
      <tr>
        <td><?=$x?></td>
        <td><?=$key->nama?></td>
        <td><?=$key->gerakan?></td>
        <td><?=$key->ekspresi?></td>
        <td><?=$key->sikap?></td>
        <td><?=$key->konsen?></td>
        <td><?=$key->prediksi?></td>
        <td><?=$key->waktu_input?></td>
      </tr>
      <?php
    }
    ?>
  </table>
</body>
</html>
