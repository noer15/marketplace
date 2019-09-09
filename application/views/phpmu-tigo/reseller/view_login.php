  <center><p class='sidebar-title text-danger produk-title'> Login Users</p> </center>

            <div class="logincontainer">
                <form method="post" action="<?php echo base_url(); ?>auth/login" role="form" id='formku'>
                     <?php echo $this->session->flashdata('message'); ?>   
                    <div class="form-group">
                        <label for="inputEmail">Username</label>
                        <input type="text" name="a" class="required form-control" placeholder="Masukkan Username" autofocus=""  minlength='5' onkeyup="nospaces(this)">
                    </div>

                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input type="password" name="b" class="form-control required" placeholder="Masukkan Password" autocomplete="off">
                    </div>

                    <div align="center">
                        <input name='login' type="submit" class="btn btn-primary" value="Login"> 
                    </div>
                </form>
                <a href="<?php echo base_url(); ?>auth/lupa" >Lupa Password?</a> <br><br> Anda Belum Punya akun? <a href="<?php echo base_url(); ?>auth/register" title="Mari gabung bersama Kami" class="link">Daftar Disini.</a>
            </div>