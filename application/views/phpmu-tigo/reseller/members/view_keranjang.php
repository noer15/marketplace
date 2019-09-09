<p class='sidebar-title text-danger produk-title'> Berikut Data Pesanan anda</p>
<?php 
echo "<form action='".base_url()."members/selesai_belanja' method='POST'>";
  echo $error_reseller; 
  if ($this->session->idp == ''){
    echo "<center style='padding:10%'><i class='text-danger'>Maaf, Keranjang belanja anda saat ini masih kosong,...</i><br>
            <a class='btn btn-warning btn-sm' href='".base_url()."members/reseller'>Klik Disini Untuk mulai Belanja!</a></center>";
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
      <?php 
        echo "<a class='btn btn-success btn-sm' href='".base_url()."members/produk_reseller/$rows[id_reseller]'>Lanjut Belanja</a>
              <a class='btn btn-danger btn-sm' href='".base_url()."members/batalkan_transaksi' onclick=\"return confirm('Apa anda yakin untuk Batalkan Transaksi ini?')\">Batalkan Transaksi</a>"; 
      ?>
      <table class="table table-striped table-condensed">
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
                    <td width='30px'><a class='btn btn-danger btn-xs' title='Delete' href='".base_url()."members/keranjang_delete/$row[id_penjualan_detail]'><span class='glyphicon glyphicon-remove'></span></a></td>
                </tr>";
            $no++;
          }
          $total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='".$this->session->idp."'")->row_array();
          echo "<tr class='success'>
                  <td colspan='5'><b>Total Berat</b></td>
                  <td><b>$total[total_berat] Gram</b></td>
                  <td></td>
                </tr>

        </tbody>
      </table>

      <div class='col-md-4 pull-right'>
        <center>Total Bayar <br><h2 id='totalbayar'></h2>   
        <button type='submit' name='submit' id='oksimpan' class='btn btn-success btn-flat btn-sm' style='display: none'>Lakukan Pembayaran</button>
        </center>
    </div>";

      $ket = $this->db->query("SELECT * FROM rb_keterangan where id_reseller='".$rows['id_reseller']."'")->row_array();
      $diskon_total = '0';
?>

<input type="hidden" name="total" id="total" value="<?php echo $total['total']; ?>"/>
<input type="hidden" name="ongkir" id="ongkir" value="0"/>
<input type="hidden" name="berat" value="<?php echo $total['total_berat']; ?>"/>
<input type="hidden" name="diskonnilai" id="diskonnilai" value="<?php echo $diskon_total; ?>"/>
<div class="form-group">
    <label class="col-sm-2 control-label" for="">Pilih Kurir</label>
    <div class="col-md-10">
        <?php       
        $kurir=array('jne','pos','tiki');
        foreach($kurir as $rkurir){
            ?>          
                <label class="radio-inline">
                <input type="radio" name="kurir" class="kurir" value="<?php echo $rkurir; ?>"/> <?php echo strtoupper($rkurir); ?>
                </label>
            <?php
        }
        ?>
    </div>
</div>
<div id="kuririnfo" style="display: none;">
    <div class="form-group">
        <div class="col-md-12">
            <div class='alert alert-info' style='padding:5px; border-radius:0px; margin-bottom:0px'>Service</div>
            <p class="form-control-static" id="kurirserviceinfo"></p>
        </div>
    </div>
</div>


<?php
echo form_close();
?>
<script>
$(document).ready(function(){

$(".kurir").each(function(o_index,o_val){
    $(this).on("change",function(){
        var did=$(this).val();
        var berat="<?php echo $total['total_berat']; ?>";
        var kota="<?php echo $rowsk['kota_id']; ?>";
        $.ajax({
          method: "get",
          dataType:"html",
          url: "<?php echo base_url(); ?>produk/kurirdata",
          data: "kurir="+did+"&berat="+berat+"&kota="+kota,
          beforeSend:function(){
            $("#oksimpan").hide();
          }
        })
        .done(function( x ) {           
            $("#kurirserviceinfo").html(x);
            $("#kuririnfo").show();         
        })
        .fail(function(  ) {
            $("#kurirserviceinfo").html("");
            $("#kuririnfo").hide();
        });
    });
});

$("#diskon").html(toDuit(0));
hitung();
});

function hitung(){
    var diskon=$('#diskonnilai').val();
    var total=$('#total').val();
    var ongkir=$("#ongkir").val();
    var bayar=(parseFloat(total)+parseFloat(ongkir));
    if(parseFloat(ongkir) > 0){
        $("#oksimpan").show();
    }else{
        $("#oksimpan").hide();
    }
    $("#totalbayar").html(toDuit(bayar));
}
</script>

<?php 
echo "<div style='clear:both'></div><hr><br>$ket[keterangan]"; 
}
?>