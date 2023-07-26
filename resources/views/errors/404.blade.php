<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="1; url={{URL::to('home')}}">
        <script type="text/javascript">
            window.location.href = "{{URL::to('home')}}"
        </script>
        <title>Page Redirection</title>
    </head>
    <body>
        <!-- Note:Automatic redirect to Home if page not found -->
        <!-- If you are not redirected automatically, follow this <a href='{{URL::to('home')}'>Home</a>. -->
    </body>
</html>
