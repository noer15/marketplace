  <center><p class='sidebar-title text-danger produk-title'> Lupa Password</p> </center>

            <div class="logincontainer">
                <form method="post" action="<?php echo base_url(); ?>auth/lupass" role="form" id='formku'>
                     <?php echo $this->session->flashdata('message'); ?>   
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input type="email" name="a" class="required form-control" placeholder="Masukkan email" autofocus=""  minlength='5' onkeyup="nospaces(this)">
                    </div>

                    <div align="center">
                        <input name="lupa" type="submit" class="btn btn-primary" value="Login"> 
                    </div>
                </form>
            </div>