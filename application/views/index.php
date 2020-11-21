<!DOCTYPE html>  
<html lang="es">
   <head>

      <link   rel="icon"       href="assets/libs/favicon.ico">
      <link   rel="stylesheet" href="assets/libs/signin.css">
      <link   rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css">
   </head>
   <body class="text-center">
      <form class="form-signin">
         <img class="mb-4" src="assets/libs/bootstrap-solid.svg" alt="" width="72" height="72">
         <h1 class="h3 mb-3 font-weight-normal">Login</h1>
         <label for="user" class="sr-only">Username</label>
         <input type="text"     id="user" class="form-control" required autofocus>
         <label for="pass" class="sr-only">Password</label>
         <input type="password" id="pass" class="form-control" autocomplete="on" required>
         <div class="checkbox mb-3">
           <label>
               <input type="text" text="" hidden>
           </label>
         </div>
         <button class="btn btn-lg btn-primary btn-block" type="submit" id="ingresar">Ingresar</button>
         <div id='mens' class="alert alert-light" ></div>
         <p class="mt-5 mb-3 text-muted">© 2019-2018</p>
      </form>

      <script>
        window.onload = function(){
          document.getElementById('ingresar').onclick = function(){
            var user = (document.getElementById('user').value).trim();
            var pass = (document.getElementById('pass').value).trim(); 
            if (user == '' || pass == '') {
                document.getElementById('mens').value = "Ingresar Usuario y Clave";
                document.getElementById('user').focus();
            } else {
                fetch('login/sesi_ingr',{
                      method: 'POST',
                      body: JSON.stringify([{user:user,pass:pass}]),
                      headers:{
                        'Content-Type':'application/json'
                      },
                      cache: 'no-cache'
                  })
                  .then(response => response.json())
                  .then(json => {
                      if (json.mensaje == ''){ 
                          document.location.href="main";
                      }else{
                          alert(json.mensaje); 
                      }
                  })
                  .catch(error => console.error('Error:', error));
                return false;
            }            
          }
        }
      </script>   
   </body>
</html>