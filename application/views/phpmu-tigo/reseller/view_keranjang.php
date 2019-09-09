<p class='sidebar-title text-danger produk-title'> Berikut Data Pesanan anda</p>
<?php 
  echo $this->session->flashdata('message'); 
  if ($this->session->idp == ''){
    echo "<center style='padding:15%'><i class='text-danger'>Maaf, Keranjang belanja anda saat ini masih kosong,...</i><br>
            <a class='btn btn-warning btn-sm' href='".base_url()."produk'>Klik Disini Untuk mulai Belanja!</a></center>";
  }else{
?>
  <table class='table table-condensed'>
  <tbody>
    <?php if (trim($rows['foto'])==''){ $foto_user = 'users.gif'; }else{ $foto_user = $rows['foto']; } ?>
    <tr bgcolor='#e3e3e3'><td rowspan='12' width='110px'><center><?php echo "<img style='border:1px solid #cecece; height:85px; width:85px' src='".base_url()."asset/foto_user/$foto_user' class='img-circle img-thumbnail'>"; ?></center></td></tr>
    <tr><th scope='row' width='120px'>Nama Pelapak</th> <td><?php echo $rows['nama_reseller']?></td></tr>
    <tr><th scope='row'>Alamat</th> <td><?php echo $rows['alamat_lengkap']?></td></tr>
    <tr><th scope='row'>Alamat Email</th> <td><?php echo $rows['email']?></td></tr>
    <tr><th scope='row'>Keterangan</th> <td><?php echo $rows['keterangan']?></td></tr>
  </tbody>
  </table>
  <hr>

      <table class="table table-striped">
          <thead>
            <tr bgcolor='#e3e3e3'>
              <th style='width:40px'>No</th>
              <th width='47%'>Nama Produk</th>
              <th>Harga</th>
              <th>Qty</th>
              <th>Berat</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
        <?php 
          $no = 1;
          foreach ($record as $row){
          $sub_total = ($row['harga_jual']*$row['jumlah'])-$row['diskon'];

          echo "<tr><td>$no</td>
                    <td><a style='color:#ab0534' href='".base_url()."produk/detail/$row[produk_seo]'>$row[nama_produk]</a></td>
                    <td>".rupiah($row['harga_jual']-$row['diskon'])."</td>
                    <td>$row[jumlah]</td>
                    <td>".($row['berat']*$row['jumlah'])." Gram</td>
                    <td>Rp ".rupiah($sub_total)."</td>
                    <td width='30px'><a class='btn btn-danger btn-xs' title='Delete' href='".base_url()."produk/keranjang_delete/$row[id_penjualan_detail]'><span class='glyphicon glyphicon-remove'></span></a></td>
                </tr>";
            $no++;
          }
          $total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."'")->row_array();
          echo "<tr class='success'>
                  <td colspan='5'><b>Total Harga</b></td>
                  <td><b>Rp ".rupiah($total['total'])."</b></td>
                  <td></td>
                </tr>

                <tr class='success'>
                  <td colspan='5'><b>Total Berat</b></td>
                  <td><b>$total[total_berat] Gram</b></td>
                  <td></td>
                </tr>

        </tbody>
      </table>

      <a class='btn btn-success btn-sm' href='".base_url()."produk/produk_reseller/$rows[id_reseller]'>Lanjut Belanja</a>
      <a class='btn btn-primary btn-sm' href='".base_url()."produk/checkouts'>Selesai Belanja</a>";

      $ket = $this->db->query("SELECT * FROM rb_keterangan where id_reseller='".$rows['id_reseller']."'")->row_array();
      echo "<hr><br>$ket[keterangan]";
}