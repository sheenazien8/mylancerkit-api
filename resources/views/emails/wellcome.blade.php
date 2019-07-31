<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wellcome To Mylancerkit</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito|Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #E4E4E4;
        }
        .header{
            font: 'Roboto', sans-serif;
        }
        .wrap{
            background-color: white;
            /*width: 50%;*/
            margin: 0 auto;
            border-radius: 10px;
        }
        .container{
            margin: 0 auto;
            padding: 1px 60px 60px 60px;
            border-radius: 10px;
            text-align: center;
        }
        .sentece{
        }
        .btn-yellow{
            text-decoration: none;
            background-color: #FFB301;
            padding: 10px;
            border: 0;
            color: white;
            border-radius: 5px;
        }
        .hero{
            /*width: 100%;*/
            height: 35em;
            border-radius: 5px 5px 0px 0px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        .social-media{

        }
        .wrap-no-color{
            /*background-color: white;*/
            margin: 0 auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="hero" style="background-image: url({{ env('APP_IMAGE_URL').'/background-image/2480553.jpg' }})">
        </div>
        <div class="container">
            <h2 class="header">Email Confirmation</h2>
            <p >Hei {{ $user->name }} Wellcome to Mylancerkit. you're almost ready to start enjoying Mylancerkit.</p>
            <p class="sentece">Simply click the big yellow button below to verify your account.</p>
        </div>
        <div class="container">
            <a href="{{ env('APP_URL').'/login?verification_token='.$user->verification_code.'&user_id='.$user->id }}"
                class="btn-yellow">Verify Account!</a>
        </div>
    </div>
    <div class="wrap-no-color">
        <div class="social-media">
            <p style="font-size: 1.2em; text-align: center; color: #8A8A8A">
                Stay With Us and enjoy your work!
            </p>
        </div>
    </div>
</body>
</html>
