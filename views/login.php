<?php
  function login() {
    
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    html, body {
      height:100%;
    }
    body {
      display:flex;
      align-items:center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <form method="post" action="login.php">
          <!-- Username input -->
          <div class="form-outline mb-4">
            <input type="text" id="form2Example1" class="form-control" />
            <label class="form-label" for="form2Example1">Username</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4">
            <input type="password" id="form2Example2" class="form-control" />
            <label class="form-label" for="form2Example2">Password</label>
          </div>

          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col d-flex justify-content-center">
              
          </div>

          <!-- Submit button -->
          <button type="button" class="btn btn-primary btn-block mb-4">Sign in</button>
        </form>
      </div>
    </div>
  </div>
</body>
