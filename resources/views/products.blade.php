<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Junk For Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body>
      
      
      <div id="app" class="container">
        <products :logged-in="{{ json_encode(Illuminate\Support\Facades\Auth::check()) }}"></products>
    </div> 

    <script src="{{ mix('/js/app.js') }}"></script>
  </body>
</html>
