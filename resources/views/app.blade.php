<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vite</title>
    @vite(['resources/css/app.css', 'resources/ts/main.ts'])
</head>

<body class="bg-base-200 font-satoshi">
<div id="app">
</div>
</body>

</html>
