<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.png">

    <title>Disrupt</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
	
    <!-- Custom styles for this template -->
    <?= stylesheet_link_tag() ?>
  </head>

  <body>
  @yield('content')
  <section id="section-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                &copy;2013 Disrupt LTD. All rights reserved.
            </div>
        </div>
    </div>
</section>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->

    <!-- <script src="js/bootstrap.min.js"></script> -->
    <!-- jQuery Include -->
    <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> -->
    <!-- Google Maps Include -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuJru-cFVq0-I0LnKIicEiaqf79aRueAM&amp;sensor=false"></script>
    <!-- Custom JS -->
    <!-- <script type="text/javascript" src="js/bootstrap-timepicker.js"></script> -->
    <!-- <script src="underscore.js" type="text/javascript"></script>     -->
    <!-- <script src="app.js" type="text/javascript"></script> -->
    <!-- <script src="writeHTML.js" type="text/javascript"></script> -->
    <?= javascript_include_tag() ?>
    <script type="text/javascript">
            $('#timepicker1').timepicker({
                autoclose: true,
                minuteStep: 1,
                showSeconds: false,
                showMeridian: false,
                defaultTime: '09:00 AM'
            });
            
            setTimeout(function() {
              $('#timeDisplay').text($('#timepicker1').val());
            }, 100);

            $('#timepicker1').on('changeTime.timepicker', function(e) {
              $('#timeDisplay').text(e.time.value);
            });     
    </script>
  </body>
</html>
