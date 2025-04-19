<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login Page</title>

        <!-- Normalize CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/modern-normalize@2.0.0/modern-normalize.min.css" />
        <link rel="stylesheet" href="index.css" />

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="logo-container">
            <img src="images/logo.svg" alt="Logo OwlBreak" />
        </div>
        <div class="loader-container">
            <svg viewBox="0 0 925 160" preserveAspectRatio="xMidYMid meet">
                <text x="50%" y="50%" dy=".32em" text-anchor="middle" class="text-body">
                    Welcome to OwlBreak.
                </text>
            </svg>
        </div>
        <div class="log-in">
            <h2>Log in into your account</h2>
        </div>
        <div class="container">
            <form action="#" method="post">
				<div>
					<input type="text" name="login-username" id="login-username" required>
					<label for="login-username">Username</label>
				</div>

				<div>
					<input type="password" name="login-pwd" id="login-pwd" required>
					<label for="login-pwd">Password</label>
				</div>

				<a href="#" id="forgot-pwd">Forgot Password?</a>

				<button class="button" id="btn-submit">
					<span class="button--text">Log In</span>

					<!-- Air -->
					<div class="button--loader">
						<div></div>
						<div></div>
						<div></div>
					</div>
					<!-- End Air -->
				</button>
			</form>
        </div>
    </body>
</html>