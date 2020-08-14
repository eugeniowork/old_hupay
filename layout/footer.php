<?php
	$version_class = new SystemVersion	;
	$version = $version_class->getLatestVersion();
?>	

<?php

	include "layout/modal/modal.php";

?>
<div class="footer">
	<img src="img/logo/lloyds logo.png" style="width:15px;"/> <strong><small>Copyright <span class="glyphicon glyphicon-copyright-mark"></span> <?php echo $version; ?></small></strong>
</div>