<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .container {
                display: table;
                margin: 100px auto 0;
            }

            div.row {
                width: 100%;
                display: table;
            }

            div.word, div.empty-space {
                height: 20px;
                width: 20px;
                border: 1px solid #333;
                line-height: 20px;
                font-size: 16px;
                background: #fff;
                text-align: center;
                display: inline-block;
                margin: 0 0 0 0;
            }

            div.empty-space {
                background: #333;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="container">
                
            </div>
        </div>
    </body>
</html>
