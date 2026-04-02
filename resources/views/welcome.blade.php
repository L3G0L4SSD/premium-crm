<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;">

    <div style="text-align:center;">
        <h1>CRM Sistemine Hoş Geldin</h1>

        <div style="margin-top:20px;">
            <a href="{{ route('login') }}" 
               style="padding:10px 20px; background:black; color:white; text-decoration:none; margin-right:10px;">
                Login
            </a>

            <a href="{{ route('register') }}" 
               style="padding:10px 20px; background:gray; color:white; text-decoration:none;">
                Register
            </a>
        </div>
    </div>

</body>
</html>