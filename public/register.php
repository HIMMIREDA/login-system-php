<?php 
  require __DIR__."/../src/bootstrap.php";
  
  require __DIR__."/../src/register.php";

?>

<?php view("header",["title"=>"Register"]) ?>


<form action="register.php" method="POST" class="border border-2 rounded-3 p-5 bg-white container-sm mt-5" style="max-width: 600px;">
  <?php flash() ?>

    <h1 class="text-center">Sign Up</h1>
  

  
    <div>
      <label class="form-label" for="username">Username:</label>
      <input type="text" name="username" id="username" class="form-control" value="<?= $inputs['username']??'' ?>">
      <small class='<?= error_class($errors,"username") ?>'><?= $errors["username"]??"" ?></small>
    </div>

    <div>
      <label class="form-label" for="email">Email:</label>
      <input type="email" name="email" id="email" class="form-control"value="<?= $inputs['email']??'' ?>">
      <small class='<?= error_class($errors,"email") ?>'><?= $errors["email"]??"" ?></small>
    </div>
    </div>

    <div>
      <label class="form-label" for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control"value="<?= $inputs['password']??'' ?>">
      <small class='<?= error_class($errors,"password") ?>'><?= $errors["password"]??"" ?></small>
    </div>
    </div>
    
    <div>
      <label class="form-label" for="password2">Password Again:</label>
      <input type="password" name="password2" id="password2" class="form-control"value="<?= $inputs['password2']??'' ?>">
      <small class='<?= error_class($errors,"password2") ?>'><?= $errors["password2"]??"" ?></small>
    </div>
    </div>

    <div class="form-check mt-2">
      <label for="agree" class="form-check-label">
        <input type="checkbox" name="agree" id="agree" value="yes" class="form-check-input">
        I agree with <a href="#" title="term of services" class="link-primary">TOS</a>
        <div class='<?= error_class($errors,"agree") ?>'><?= $errors["agree"]??"" ?></div>
    </div>
      </label>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-2">Register</button>

    <footer class="mt-4 text-center">
      Already a member <a href="login.php" class="link-primary">Login here</a>
    </footer>
    
  
</form>

<?php view("footer"); ?>