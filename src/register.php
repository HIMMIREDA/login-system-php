<?php 
  
  $errors=[];
  $inputs=[];

  if(is_post_request()){
    
      $fields=[
        "username"=>"string | required | between:3,25 | username |unique:users,username",
        "email"=>"email | required | email | max:319 |unique:users,email",
        "password"=>"string | max:255 |required | secure",
        "password2"=>"string | required | same:password",
        "agree"=>"string | required"
      ];

      $messages=[
        "password2"=>[
          "required"=>"Please enter the password again",
          "same"=>"passwords dont match"
        ],
        "agree"=>[
          "required"=>"you need to agree to the term of services to register"
          ]
      ];

      [$inputs,$errors]=filter($_POST,$fields,$messages);
      if($errors){
        redirect_with("register.php",[
          "inputs"=>$inputs,
          "errors"=>$errors
        ]);
      }

      if(register_user($inputs["email"],$inputs["username"],$inputs["password"])){
        redirect_with_message("login.php","Your account has been created successfully check your inbox to activate your account.Please Login here.");
      }
  }elseif(is_get_request()){
    [$inputs,$errors]=session_flash("inputs","errors");
    
  }




?>