<div class="content">
<div class="row">
  <!-- left column -->
  <div class="col-xs-12">
    <!-- general form elements -->
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Profil Pengguna</h3>
      </div><!-- /.box-header -->
      <!-- form start -->
      <form class="memberProfile" role="form" method="post" action="user/SetMemberProfile">
        <div class="box-body">
          <div class="row">
            <div class="col-sm-6">
               <div class="form-group">
                  <label for="username">Nama Pengguna</label>
                  <input type="text" name="username" id="username" class="form-control" value="<?php echo $data['NamaPengguna']?>">
                </div>
                <div class="form-group">
                  <label for="katasandi">Kata Sandi</label>
                  <input type="password" name="katasandi" id="katasandi" placeholder= "Abaikan jika tidak ada perubahan kata sandi" class="form-control">
                </div>
                <div class="form-group">
                  <label for="katasandilagi">Kata Sandi Lagi</label>
                  <input type="password" name="katasandilagi" id="katasandilagi" placeholder= "Abaikan jika tidak ada perubahan kata sandi" class="form-control">
                </div>      
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                  <label for="namalengkap">Nama Lengkap</label>
                  <input type="text"name="nama"  id="nama" class="form-control" value="<?php echo $data['NamaLengkap']?>">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" name="email" id="email" class="form-control" value="<?php echo $data['Email']?>">
                </div>
            </div> 
          </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
          <button class="btn btn-sm btn-primary" id="btnSimpan">Simpan</button>
        </div>
      </form>
    </div><!-- /.box -->
  </div><!--/.col (right) -->
</div>
</div>

<script>
    $(document).ready(function(e)
    {       
        $('.memberProfile').submit(function(e)
        {
            e.preventDefault();     
            sendRequestForm($(this).attr('action'), $(this).serialize(), 'memberProfile');    
        });
    });     
</script>