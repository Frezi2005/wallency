<!DOCTYPE html>
<html>
<head>
	<?php
		echo $this->Html->script('main');
		echo $this->Html->css('grid');
		echo $this->Html->css('layout');
		echo $this->Html->script('https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js');
		echo $this->Html->script('https://cdn.jsdelivr.net/npm/sweetalert2@9');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div class="blur">
		<nav>
			<span class="logo col-fhd-2 col-hd-2"><a href="home">LOGO</a></span>
			<div class="menu col-fhd-6 col-hd-6">
				<ul>
					<li><a href="home">Home</a></li>
					<li><a href="about">About</a></li>
					<li><a href="contact">Contact</a></li>
					<li><a href="career">Career</a></li>
				</ul>
			</div>
			<span class="registerAndLogin col-hd-2 col-fhd-2">
				<span class="register"><a href="register">Register</a></span> 
				| 
				<span class="login"><a href="login">Login</a></span>
			</span>
		</nav>
		<div class="container">
			<?php echo $this->fetch('content');?>
		</div>
		<div class="footer">
			<div class="text col-fhd-8 col-hd-8">
				<div class="menu">
					<ul>
						<li>Home</li>
						<li>About</li>
						<li>Contact</li>
						<li>Career</li>
					</ul>
				</div><br />
				<span class="logo col-fhd-2 col-hd-2">WALLENCY</span>
				<span>Copyright &copy; <?= date('Y')?> Wallency.
				Wallency was created by Kamil Waniczek.</span>
				<span class="privacy-policy">
					<a href="app/webroot/files/privacyPolicy.pdf" target="_blank">Privacy Policy</a>
					<a href="terms-of-service">Terms of Service</a>
				</span>
			</div>	
		</div>
	</div>
	<?php
		if(!isset($_COOKIE['CakeCookie']['rodo_accepted'])) {
			echo $this->element('rodo-modal');
		}
	?>
</body>
</html>
