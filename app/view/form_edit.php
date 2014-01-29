<?php
 $id = $_GET['id'];
 $sql = "SELECT * FROM siswa WHERE id='$id'";
 $result = mysql_query($sql);
 $r=mysql_fetch_array($result);
?>
<h2>Buku Telepon</h2>
 
<h3>Form Edit</h3>
<form method="post" action="do_update.php">
<input type="hidden" name="id" value="<?php echo $r->id; ?>">
<table>
 <tr>
     <td>Nama</td>
     <td><input type="text" name="nama" value="<?php echo $r['nama']; ?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" value="Simpan"></td>
  </tr>
</table>    
</form>