<?php
  require __DIR__."/../src/bootstrap.php";
  require __DIR__."/../src/login.php";


  
?>


<?php view("header",["title"=>"Login"]); ?>


<form action="login.php" method="POST" class="border border-2 rounded-3 p-5 bg-white container-sm mt-5" style="max-width: 600px;">

    <?= isset($errors["login"])?"<div class='alert alert-danger text-center'>{$errors['login']}</div>": "" ?>
    <?php flash() ?>
    <h1 class="text-center">Login</h1>
  

  
    <div>
      <label class="form-label" for="username">Username:</label>
      <input type="text" name="username" id="username" class="form-control" value="<?= $inputs['username']??'' ?>">
      <small class='<?= error_class($errors,"username") ?>'><?= $errors["username"]??"" ?></small>
    </div>

    <div>
      <label class="form-label" for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control"value="<?= $inputs['password']??'' ?>">
      <small class='<?= error_class($errors,"password") ?>'><?= $errors["password"]??"" ?></small>
    </div>
    

    
    

    
    <footer class="mt-4 d-flex d-flex justify-content-between">
        <button type="submit" class="btn btn-primary  mt-2">Login</button>
         <div class="align-self-center"><a href="register.php" class="link-primary ">Register</a></div>
    </footer>
    
  
</form>

<?php view("footer"); ?>