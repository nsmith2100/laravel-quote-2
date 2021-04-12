<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/css/app.css" rel="stylesheet">
        <title>Laravel</title>
        <script language="javascript">
            const clickLogin = async () => {
                console.log("in Click login");
                const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
                    method: 'POST',
                    body: JSON.stringify({
                        'email': document.getElementById("email_address").value,
                        'password': document.getElementById("email_address_pwd").value
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if(data.access_token !== undefined) {
                        localStorage.setItem("bearerToken", data.access_token);

                        document.getElementById('information_div').classList.remove("hidden");
                        document.getElementById('login_div').classList.add("hidden");
                    } else {
                        alert("The email or password is incorrect");
                    }

                }).catch((error) => {
                    alert("The email or password is incorrect");
                });
            }

            const clickRegister = async () => {
                console.log("in Click Register");
                const response = await fetch('http://127.0.0.1:8000/api/auth/register', {
                    method: 'POST',
                    body: JSON.stringify({
                        'name': document.getElementById("email_address").value,
                        'email': document.getElementById("email_address").value,
                        'password': document.getElementById("email_address_pwd").value,
                        'password_confirmation': document.getElementById("email_address_pwd").value
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const myJson = await response.json();
                console.log(myJson);
                if(myJson.access_token !== nil){
                    localStorage.setItem("bearerToken", myJson.access_token);
                    clickLogin();
                }

            }

            const clickLogout = async () => {
                console.log("Clicked Logout");

                const response = await fetch('http://127.0.0.1:8000/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.bearerToken
                    }
                });
                const myJson = await response.json();
                console.log(myJson);
                

                document.getElementById("email_address_pwd").value = "";
                document.getElementById("email_address").value = "";

                document.getElementById('information_div').classList.add("hidden");
                document.getElementById('login_div').classList.remove("hidden");
            }

            const clickBackButton = async () => {
                document.getElementById('information_div').classList.remove("hidden");
                document.getElementById('information_display_div').classList.add("hidden");
            }
        </script>
    </head>
    <body class="antialiased">
        <div id="body_content">
           <h1>Get A Quote</h1>
           <div id="login_div">
                <div>
                    <label for="email_address" >Email Address:</label>
                    <div>
                        <input type="text" id="email_address" name="email_address"  />
                    </div>
                </div>
                <div>
                    <label for="email_address_pwd" >Password:</label>
                    <div>
                        <input type="password" id="email_address_pwd" name="email_address_pwd"  />
                    </div>
                </div>
                <div>
                    <button id="btn_login" name="btn_login" onclick="clickLogin()">Login</button>
                    <button id="btn_login" name="btn_login" onclick="clickRegister()">Register</button>
                </div>
           </div>
           <div id="information_div" class="hidden">

           </div>
           <div id="information_display_div" class="hidden">
            
           </div>
        </div>
    </body>
</html>
