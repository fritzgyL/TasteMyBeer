<?php
    if(!empty($errors)){
      foreach ($errors as $error){
      ?>
       <div class="alert alert-danger" role="alert">
           <?php echo $error; ?>
     </div>
<?php   
  }   
    }
    ?>
<!-- Alert-->

       <!-- Section-->
   <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fw-light fs-5">Modifier votre mot de passe</h5>
            <form action="<?php echo PAGE_NEW_PASSWORD; ?>" method="POST">

              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="password">
                <label for="floatingInput">Nouveau mot de passe</label>
              </div>
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="confirmPassword">
                <label for="floatingInput">Confirmer le mot de passe</label>
              </div>

              <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Valider</button>
              </div>
              <hr class="my-4">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
