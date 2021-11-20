<!DOCTYPE html>  
<html lang="es">
  <head>
    <title>Project Sky</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/libs/favicon.ico">
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libs/signin.css">
  </head>
  <body class="text-center">
    <form class="form-signin" id="formLogin">
      <img class="mb-4" src="assets/libs/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Login</h1>
      <label for="user" class="sr-only">Username</label>
      <input type="text" id="user" class="form-control" pattern="[A-Za-z0-9._-]{1,30}" maxlength="30" required autofocus>
      <label for="pass" class="sr-only">Password</label>
      <input type="password" id="pass" class="form-control" autocomplete="on" maxlength="30" required>
      <div class="form-group form-check" hidden>
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit" id="ingresar">Ingresar</button>
      <div class="invalid-feedback" id="msg">Please provide a valid city.</div>
      <p class="mt-5 mb-3 text-muted">© 2020-2021</p>
    </form>
    <script>
      window.onload = function(){
        document.getElementById('ingresar').onclick = function(){
          if(document.getElementById('formLogin').checkValidity()){
            var user = (document.getElementById('user').value).trim();
            var pass = (document.getElementById('pass').value).trim(); 
            if (user == '' || pass == '') {
                document.getElementById('msg').value = "Ingresar Usuario y Clave";
                document.getElementById('msg').classList.add("d-block");
                document.getElementById('user').focus();
            } else {
              fetch('login/auth?user='+user+'&pass='+pass,{method: 'GET',cache: 'no-cache'})
              .then(response => response.json())
              .then(json => {
                if (json.cod == '200'){
                  localStorage.setItem("token",json.res.tkn);
                  document.location.href="main";
                }else{
                  document.getElementById('msg').innerHTML = json.msg;
                  document.getElementById('msg').classList.add("d-block");
                }
              })
              .catch(error => console.log('Error:', error));
              setTimeout(() => {document.getElementById('msg').classList.remove("d-block");},5000);
              return false;
            } 
          }
        }
      }
    </script>   
  </body>
</html>