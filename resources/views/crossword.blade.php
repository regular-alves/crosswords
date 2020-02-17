<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cross Words Generator</title>

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

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .container {
                display: table;
                margin: 100px auto 0;
            }

            .row {
                width: 100%;
                display: table;
            }

            .row .field {
                height: 30px;
                width: 30px;
                border-width: 0 0 1px 1px;
                border-style: solid;
                border-color: #636b6f;
                line-height: 30px;
                font-size: 21px;
                background: #fff;
                text-align: center;
                display: inline-block;
                margin: 0 0 0 0;
            }

            .row:first-child .field {
                border-top-width: 1px;
            }

            .row .field:last-child {
                border-right-width: 1px;
            }

            .row:first-child .field:first-child {
                border-radius: 4px 0 0 0;
            }

            .row:first-child .field:last-child {
                border-radius: 0 4px 0 0;
            }

            .row:last-child .field:first-child {
                border-radius: 0 0 0 4px;
            }

            .row:last-child .field:last-child {
                border-radius: 0 0 4px 0;
            }

            .row .empty-space {
                background: #636b6f;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="container">
            @foreach($crosswords as $row)
                <div class="row">
                @foreach($row as $letter)
                    <div class="field <?php echo $letter!==false ? 'letter' : 'empty-space'?>"><?php echo $letter ? $letter : "&nbsp;" ?></div>
                @endforeach
                </div>
            @endforeach
            </div>
            <p><a href="?states=<?php echo $states ?>">próximo</a></p>
        </div>
    </body>
</html>