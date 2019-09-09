<p class='sidebar-title text-danger produk-title'> Detail Pesanan Anda</p>
  <table class='table table-condensed'>
  <tbody>
    <?php if (trim($rows['foto'])==''){ $foto_user = 'users.gif'; }else{ $foto_user = $rows['foto']; } ?>
    <tr bgcolor='#e3e3e3'><td rowspan='12' width='110px'><center><?php echo "<img style='border:1px solid #cecece; height:85px; width:85px' src='".base_url()."asset/foto_user/$foto_user' class='img-circle img-thumbnail'>"; ?></center></td></tr>
    <tr><th scope='row' width='100px'>Nama</th> <td><?php echo $rows['nama_reseller']?></td></tr>
    <tr><th scope='row'>Alamat</th> <td><?php echo "Kota $rows[nama_kota], $rows[alamat_lengkap]"; ?></td></tr>
    <tr><th scope='row'>Kontak</th> <td><?php echo "Hp. <b style='color:green'>$rows[no_telpon]</b>, Email. <i style='text-decoration:underline; color:blue'>$rows[email]</i>"; ?></td></tr>
    <tr><th scope='row'>Keterangan</th> <td><?php echo $rows['keterangan']?></td></tr>
  </tbody>
  </table>
  <hr>

      <table class="table table-striped table-condensed">
          <thead>
            <tr bgcolor='#e3e3e3'>
              <th style='width:40px'>No</th>
              <th width='50%'>Nama Produk</th>
              <th>Harga</th>
              <th>Qty</th>
              <th>Berat</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
        <?php 
          $no = 1;
          foreach ($record as $row){
          $sub_total = ($row['harga_jual']*$row['jumlah'])-$row['diskon'];
          echo "<tr><td>$no</td>
                    <td>$row[nama_produk]</td>
                    <td>".rupiah($row['harga_jual'])."</td>
                    <td>$row[jumlah]</td>
                    <td>".($row['berat']*$row['jumlah'])." Kg</td>
                    <td>Rp ".rupiah($sub_total)."</td>
                </tr>";
            $no++;
          }
          $detail = $this->db->query("SELECT * FROM rb_penjualan where id_penjualan='".$this->uri->segment(3)."'")->row_array();
          $total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='".$this->uri->segment(3)."'")->row_array();
          if ($rows['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; $status = 'Proses'; }elseif($rows['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; }else{ $proses = '<i class="text-info">Konfirmasi</i>'; }
          echo "
                <tr class='success'>
                  <td colspan='5'><b>Berat</b> <small><i class='pull-right'>(".terbilang($total['total_berat'])." Gram)</i></small></td>
                  <td><b>$total[total_berat] Gram</b></td>
                </tr>

                <tr class='success'>
                  <td colspan='5'><b><span style='text-transform:uppercase'>$detail[kurir]</span> - $detail[service]</b> <small><i class='pull-right'>(".terbilang($detail['ongkir']).")</i></small></td>
                  <td><b>Rp ".rupiah($detail['ongkir'])."</b></td>
                </tr>

                <tr class='success'>
                  <td colspan='5'><b>Total </b> <small><i class='pull-right'>(".terbilang($total['total'])." Rupiah)</i></small></td>
                  <td><b>Rp ".rupiah($total['total'])."</b></td>
                </tr>

                <tr class='warning'>
                  <td style='color:Red' colspan='5'><b>Subtotal </b> <small><i class='pull-right'>(".terbilang($total['total']+$detail['ongkir'])." Rupiah)</i></small></td>
                  <td style='color:Red'><b>Rp ".rupiah($total['total']+$detail['ongkir'])."</b></td>
                </tr>

                
                <tr class='danger'><td align=center colspan='6'><b>$proses</b></td></tr>

        </tbody>
      </table>";