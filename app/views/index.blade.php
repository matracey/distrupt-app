@extends('layouts.master')
@section('content')
    <!-- Fixed navbar -->

    <div class="navbar navbar-inverse navbar-fixed-top text-center" id="topheader">
      <div class="container">
        <div class="navbar-header">
          {{ HTML::link('index.html', HTML::image('assets/logo.png', 'Disrupt', ['class' => "logobrand"]), []) }}
        </div>
      </div>
    </div>

<section id="home">
	<div class="container text-center">
		<div class="row">
			<div class="col-xs-12">		
				<h3>Often late? Next time use</h3><br>
				{{ HTML::image('assets/logo.png', 'Disrupt', ['class' => "logomain"]) }}
				<p>Automatically notify people via SMS or email of TFL disruptions delaying your work journey</p>
			</div>
		</div><hr>
		<div class="row">
			<h4>How does it work?</h4><br>
				<div class="col-xs-4">		
					<p class="bolden"><span style="background: #E32017;" class="glyphicon home glyphicon-random"></span><br>Tell us your commute route</p>
				</div>
				<div class="col-xs-4">		
					<p class="bolden"><span style="background: #003688;" class="glyphicon home glyphicon-envelope"></span><br>Pick the people to notify if you're late</p>
				</div>
				<div class="col-xs-4">		
					<p class="bolden"><span style="background: #000000;" class="glyphicon home glyphicon-bullhorn"></span><br>We'll notify them if your commute is delayed</p>
				</div>
		</div>
		<div class="row" style="background-color:#efefef;padding:20px 0px 20px 0px;margin-bottom:-50px;">
			<div class="col-xs-12">
				<h4>Always useful, always free</h4>
				<a class="btn btn-lg btn-primary" id="login">Login</a>				
			</div>
		</div>
	</div>
</section>
<section id="login-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h4 class="titlepage">Login now to get started</h4>
				<form role="form">
				  <div class="form-group">
					<label for="Email">Email address</label>
					<input type="email" class="form-control" id="Email" placeholder="Enter email">
				  </div>
				  <div class="form-group">
					<label for="Password">Password</label>
					<input type="password" class="form-control" id="Password" placeholder="Password">
				  </div>
				  <a type="submit" id="login-button" class="btn btn-success">Login</a>&nbsp; <!-- or <a href="register.html" class="btn btn-default">Register</a> -->
				</form>
				<!--<br><a href="home.html" id="header-button" class="btn btn-link">&larr; Home</a>-->
			</div>
		</div>
	</div>
</section>
<section id="choosecommute-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h4 class="titlepage">Journey Details</h4>			
					<form role="form" id="choosecommute">
							<label for="time">What time do you need to be at work? (HH:MM in 24 hours):</label>
							 <div class="input-group bootstrap-timepicker">                                       
								<span class="input-group-btn">
									<button class="btn default" type="button"><span class="glyphicon glyphicon-time"></span></button>
								</span>
								<input type="text" id="timepicker1" class="form-control timepicker-24" name="form-time" value='#timeDisplay'>
							 </div><br>
							<label for="time">What are the start and end points of your journey?</label>
							<div class="form-group">
							  <div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span><input type="email" class="form-control" name="start-postcode" id="start-postcode" placeholder="Start Location (post code or street address)">
							  </div>
							</div>
							<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span><input type="email" class="form-control" name="end-postcode" id="end-postcode" placeholder="End Location (post code or street address)">
							  </div>				
							</div>  
					</form>
						<div class="clearfix">
							<!-- <a class="btn btn-default pull-left" id="login-button" >&larr; Previous</a> -->
							<a class="btn btn-primary pull-right" id="choosecommute-next-button">Next &rarr;</a>
						</div>
			</div>
		</div>
	</div>
</section>
<section id="chooseroute-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h4 class="titlepage">Select Your Tube Route</h4>				
						<div class="btn-group-vertical" id="routes">

						</div><br><br>
				<div class="clearfix">
					<!-- <a class="btn btn-default pull-left">&larr; Previous</a> -->
					<a class="btn btn-primary pull-right" id="chooseroute-next-button">Next &rarr;</a>
				</div>					
			</div>
		</div>
	</div>
</section>
<section id="whotoalert-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h4 class="titlepage">Select People to Notify if you're late</h4>				
					<div class="panel-group" id="accordion">
					  <div class="panel panel-default">
						<div class="panel-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						  <h4 class="panel-title">
							  Send a SMS to this numbers
						  </h4></a>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in">
							<div class="panel-body" id="phone-numbers-div">
								<form id="add-number-form">
									<input type="text" name="add-number" /> <a class="btn-sm btn-primary" id="add-number-button"><span class="glyphicon glyphicon-plus-sign"></span></a><br/>
								</form>
							</div>
						</div>
					  </div>
					  <div class="panel panel-default">
						<div class="panel-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
						  <h4 class="panel-title">
							  Send an email to this addresses
						  </h4></a>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse">
							<div class="panel-body" id="email-addresses-div">
								<form id="add-email-form">
									<input type="text" name="add-email" /> <a class="btn-sm btn-primary" id="add-email-button"><span class="glyphicon glyphicon-plus-sign"></span></a><br/>
								</form>
							</div>
						</div>
					  </div>
					</div><br>						
						<div class="form-group">
							<div class="clearfix">
								<!-- <a class="btn btn-default pull-left">&larr; Previous</a> -->
								<a class="btn btn-success pull-right" id="whotoalert-next-button">Finish</a>
							</div>				
						</div>	
			</div>	
		</div>
	</div>
</section>
<section id="confirmation-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="confirmation-message">

				</div>
				<br><div class="form-group">
					<div class="clearfix">
						<!-- <a class="btn btn-default pull-left">&larr; Previous</a> -->
						<!-- <a class="btn btn-primary pull-right" href="home.html">Home</a> -->
					</div>				
				</div>					
			</div>			
		</div>
	</div>
</section>
@endsection