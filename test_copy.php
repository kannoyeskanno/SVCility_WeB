<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .parent {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(5, 1fr);
            grid-gap: 7px;
        }

        .div-1 { grid-area: 1 / 1 / 6 / 3; }
        .div-2 { grid-area: 1 / 1 / 2 / 3; }
        .div-3 { grid-area: 2 / 1 / 3 / 3; }
        .div-4 { grid-area: 3 / 1 / 4 / 2; }
        .div-5 { grid-area: 3 / 2 / 4 / 3; }
        .div-6 { grid-area: 4 / 1 / 5 / 2; }
        .div-7 { grid-area: 4 / 2 / 5 / 3; }
        .div-8 { grid-area: 5 / 1 / 6 / 3; }

        div {
            border: solid;
        }
    </style>
</head>
<body>
    <div class="parent">
        <div class="div-1">
            <div class="div-2">2</div>
            <div class="div-3">3</div>
            <div class="div-4">4</div>
            <div class="div-5">5</div>
            <div class="div-6">6</div>
            <div class="div-7">7</div>
            <div class="div-8">8</div>

        </div>
        
        
    </div>
</body>
</html>
