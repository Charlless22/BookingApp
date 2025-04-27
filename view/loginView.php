<!DOCTYPE html>
<html>
    <head>
        <title>BookIt</title>
        <link rel="stylesheet" href="../css/login.css">
        <link rel="icon" type="image/png" href="../assets/logo.png">
    </head>
    <body>
        <div class="container">
            <div class="top-page"></div>
            <div class="middle-page">
                <div class="id-container">
                    <form class="form-container">
                        <div class="logo-container">
                            <img src="../assets/logo.png" width="100px" height="100px">
                        </div>
                        <div class="credentials-container">
                            <div class="input-container">
                                <label>Email</label>
                                <input type="email" placeholder="email@email" required/>
                            </div>
                            <div class="input-container">
                                <label>Password</label>
                                <input type="password" placeholder="password" required/>
                            </div>
                        </div>
                        <hr style="width:90%; border-top: 1px solid; border-color: #000000;">
                        <div class="links-container">
                            <a href="signUpView.php" style="color: #000">Sign up</a>
                            <a href="forgetPasswordView.php" style="color: #000">Forget Password</a>
                        </div>
                        <div class="button-container">
                            <button class="button">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bottom-page"></div>
        </div>
    </body>
    <footer>
    </footer>
</html>
