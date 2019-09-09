<?php
$rows = $this->db->query("SELECT a.*, b.nama_kota, c.nama_provinsi FROM `rb_reseller` a JOIN rb_kota b ON a.kota_id=b.kota_id
JOIN rb_provinsi c ON b.provinsi_id=c.provinsi_id where a.id_reseller='$record[id_reseller]'")->row_array();
echo "<div class='col-md-12'>
    <div class='col-md-9' style='padding:0px'>
        <div class='col-md-3' style='padding:0px'>";
        if ($record['gambar'] != ''){ 
            $ex = explode(';',$record['gambar']);
            $hitungex = count($ex);
            for($i=0; $i<1; $i++){
                if (file_exists("asset/foto_produk/".$ex[$i])) { 
                    if ($ex[$i]==''){
                        echo "<img style='height:120px; width:100%;  border:1px solid #cecece' src='".base_url()."asset/foto_produk/no-image.jpg'>";
                    }else{
                        echo "<a target='_BLANK'  href='".base_url()."asset/foto_produk/".$ex[$i]."'><img class='' style='width:100%; border:1px solid #cecece' src='".base_url()."asset/foto_produk/".$ex[$i]."'></a>";
                    }
                }else{
                    echo "<img style='height:120px; width:100%;  border:1px solid #cecece' src='".base_url()."asset/foto_produk/no-image.jpg'>";
                }
            }

            echo "<center style='margin-top:5px'>";
            for($i=1; $i<$hitungex; $i++){
                if (file_exists("asset/foto_produk/".$ex[$i])) { 
                    if ($ex[$i]==''){
                        echo "<img style='width:24%; border:1px solid #fff' src='".base_url()."asset/foto_produk/no-image.jpg'>";
                    }else{
                        echo "<a target='_BLANK'  href='".base_url()."asset/foto_produk/".$ex[$i]."'><img class='' style='width:24%; border:1px solid #fff' src='".base_url()."asset/foto_produk/".$ex[$i]."'></a>";
                    }
                }else{
                    echo "<img style='width:24%;  border:2px solid #fff' src='".base_url()."asset/foto_produk/no-image.jpg'>";
                }
            }
            echo "</center>";
        }else{
            echo "<i style='color:red'>Gambar / Foto untuk Produk ini tidak tersedia!</i>";
        }
        $kat = $this->model_app->view_where('rb_kategori_produk',array('id_kategori_produk'=>$record['id_kategori_produk']))->row_array();
        $jual = $this->model_reseller->jual_reseller($record['id_reseller'],$record['id_produk'])->row_array();
        $beli = $this->model_reseller->beli_reseller($record['id_reseller'],$record['id_produk'])->row_array();
        $disk = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='$record[id_produk]'")->row_array();
        $diskon = rupiah(($disk['diskon']/$record['harga_konsumen'])*100,0)."%";
        if ($disk['diskon']>0){ $diskon_persen = "<div class='top-right'>$diskon</div>"; }else{ $diskon_persen = ''; }
        if ($disk['diskon']>=1){ 
          $harga_konsumen =  "Rp ".rupiah($record['harga_konsumen']-$disk['diskon']);
          $harga_asli = "Rp ".rupiah($record['harga_konsumen']);
        }else{
          $harga_konsumen =  "Rp ".rupiah($record['harga_konsumen']);
          $harga_asli = "";
        }

        echo "<div style='clear:both'></div><center style='color:green;'><i>Klik untuk lihat ukuran besar.</i></center>
        </div>

        <div class='col-md-9' style='padding:0px'>
            <div style='margin-left:10px'>
            <h1>$record[nama_produk]</h1>"; ?>

           
            <?php echo "<table class='table table-condensed' style='margin-bottom:0px'>
                <tr><td colspan='2' style='color:red;'><del style='color:#8a8a8a'>$harga_asli</del><br>
                <h1 style='display:inline-block'>$harga_konsumen</h1> / $record[satuan] 
                
                </td></tr>
                <tr><td style='font-weight:bold; width:90px'>Berat</td> <td>$record[berat] Gram</td></tr>
                <tr><td style='font-weight:bold'>Kategori</td> <td><a href='".base_url()."produk/kategori/$kat[kategori_seo]'>$kat[nama_kategori]</a></td></tr>";
                if (($beli['beli']-$jual['jual'])>=1){
                    echo "<tr><td style='font-weight:bold'>Tersedia</td> <td class='text-success'>".($beli['beli']-$jual['jual'])." stok barang</td></tr>";
                }else{
                    echo "<tr><td style='font-weight:bold'>Stok</td> <td>Tidak Tersedia</td></tr>";
                }

            echo "</table>";

            if ($this->session->level=='konsumen'){
                echo "<center><a class='btn btn-success btn-block btn-lg' href='".base_url()."members/keranjang/$record[id_reseller]/$record[id_produk]'>Beli Sekarang</a></center>";
            }else{
                echo "<center><a class='btn btn-success btn-block btn-lg' href='".base_url()."produk/keranjang/$record[id_reseller]/$record[id_produk]'>Beli Sekarang</a></center>";
            }

        echo "
        </div>
        </div>
       

    </div>

    <div class='col-md-3' style='padding:0px'>";
        include "sidebar_pelapak.php";
    echo "</div>

</div>
<div style='clear:both'><br></div>";
?>
