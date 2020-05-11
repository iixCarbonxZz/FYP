<?php
	/*Starts the session to load previously stored variables*/
	session_start();

	require 'src\connect.php';
	include 'src\logout.php';
	include 'src\sessionUpdate.php';

?>
<html>
<head>
  <link id="cssLoad" type="text/css" rel="stylesheet" href="css\default.css" />
</head>
<body class="color-dark-bg">
	<?php
		include 'navbar.php';
	?>
  <div class="buffer">

  </div>
  <div class="content-wrapper color-mid-bg">
    <h1 class="content-header color-dark-bg">Header</h1>
    <p class="content-main">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sem justo, luctus quis tincidunt vel, dignissim non mauris. Proin feugiat risus orci, in ornare dui posuere in. Vestibulum accumsan vitae quam a blandit. Maecenas facilisis urna id convallis consequat. Nulla vitae tincidunt lacus. Nam ex lorem, pulvinar vitae semper eget, consequat non mauris. Mauris posuere, elit ac aliquet sodales, elit nunc iaculis massa, sit amet posuere sem augue non sapien. Pellentesque ultrices lacinia varius. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut at semper lectus. Sed sed magna eu elit porttitor scelerisque sit amet id libero. Etiam at justo sapien. Curabitur in ullamcorper nibh. Aliquam laoreet aliquet neque nec faucibus.

Curabitur semper nisl et augue mattis posuere. Donec felis ex, pretium vitae dictum eget, auctor vehicula diam. Etiam bibendum diam eu nulla semper, suscipit hendrerit libero lacinia. Sed maximus, diam sed eleifend egestas, odio tellus efficitur turpis, vel fringilla turpis neque ac nulla. Nulla rhoncus felis tellus, a eleifend dui sollicitudin a. Etiam bibendum fermentum hendrerit. Morbi faucibus eget ipsum nec tincidunt. Nam non nibh nulla.

Suspendisse et condimentum lorem. Praesent finibus, libero quis fermentum hendrerit, felis risus consequat augue, et euismod ante nisl pellentesque neque. Nam luctus elementum neque non sollicitudin. Nam at finibus justo. Donec volutpat, dui vitae luctus convallis, ex justo rutrum tellus, vitae congue sapien lectus vel arcu. Nam in ante lorem. Ut pharetra porttitor dui vitae dignissim. Nullam scelerisque accumsan augue, aliquet porta purus sollicitudin id. Duis sed magna a risus tincidunt feugiat sed blandit tortor. Praesent varius nunc quis enim scelerisque, nec porttitor velit ornare.

Integer volutpat eros metus, nec tempus nulla dapibus sit amet. Vestibulum nec neque id massa porta mattis. Vestibulum ullamcorper aliquet nulla pellentesque dignissim. Nulla aliquet pharetra tristique. Vivamus ipsum ligula, faucibus vel urna et, dapibus sollicitudin diam. Phasellus scelerisque mauris vitae ullamcorper interdum. Fusce elit odio, consectetur quis mauris sit amet, consectetur lacinia ligula. Vivamus tristique orci risus, id convallis tellus scelerisque et. Quisque facilisis blandit ante nec consectetur. Nullam ipsum tortor, maximus fermentum mi vitae, iaculis sollicitudin eros. Praesent pulvinar eros ut nisi porttitor iaculis.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque nec enim vitae augue varius viverra. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed eleifend, mi ac rutrum aliquam, turpis nisi tristique dolor, tempus dapibus orci mauris vitae erat. Praesent congue tempor mauris, blandit mattis dolor efficitur eget. Pellentesque egestas iaculis odio, eu feugiat lectus. Cras semper, velit vitae sollicitudin imperdiet, enim sem pretium magna, id vehicula neque sem non justo. Phasellus non malesuada eros. Vestibulum vitae tempor sapien. Donec non volutpat lacus. Vestibulum blandit, neque quis consectetur ultricies, lacus tortor feugiat massa, dictum maximus turpis arcu iaculis libero. Nullam in viverra dui. Integer interdum nunc vel magna sodales feugiat. Praesent eget diam mi.
    </p>
  </div>
</body>
</html>
