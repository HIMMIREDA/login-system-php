<?php 
  require __DIR__."/../src/bootstrap.php";
  

  if(is_post_request()){
      $fields=[
        "username"=>"string | required | between:3,25 | unique:users,username",
        "email"=>"email | required | email | unique:users,email",
        "password"=>"string | required | secure",
        "agree"=>"string | required"
      ];

      $messages=[
        "password2"=>[
          "required"=>"Please enter the password again","same"=>"passwords dont match"
        ],
        "agree"=>[
          "required"=>"you need to agree to the term of services to register"
          ]
      ];

      [$inputs,$errors]=filter($_POST,$fields,$messages);
  }




?>

<?php view("header",["title"=>"Register"]) ?>


<form action="register.php" method="POST" class=" w-50 p-5 bg-white">
  <header>
    <h1 class="text-center">Sign Up</h1>
  </header>

  
    <div>
      <label class="form-label" for="username">Username:</label>
      <input type="text" name="username" id="username" class="form-control">
    </div>

    <div>
      <label class="form-label" for="email">Email:</label>
      <input type="email" name="email" id="email" class="form-control">
    </div>

    <div>
      <label class="form-label" for="password">Password:</label>
      <input type="password" name="password" id="password" class="form-control">
    </div>
    
    <div>
      <label class="form-label" for="password2">Password Again:</label>
      <input type="password" name="password2" id="password2" class="form-control">
    </div>

    <div class="form-check mt-2">
      <label for="agree" class="form-check-label">
        <input type="checkbox" name="agree" id="agree" value="yes" class="form-check-input">
        I agree with <a href="#" title="term of services" class="link-primary">TOS</a>
      </label>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-2">Register</button>

    <footer class="mt-4 text-center">
      Already a member <a href="login.php" class="link-primary">Login here</a>
    </footer>
    
  
</form>

<?php view("footer"); ?>