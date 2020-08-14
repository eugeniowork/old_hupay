<?php
session_start();
include "../class/connect.php";
include "../class/memorandum_class.php";


if (isset($_REQUEST['removeArray']) && isset($_GET["memo_id"])){

	$memorandum_class = new Memorandum;

	$removeArray = isset($_REQUEST['removeArray']) ? $_REQUEST['removeArray'] : "";

	$removeArray = trim($removeArray,"[]");

	$remove_array = explode(",", $removeArray);

	$remove_array_count = count($remove_array);


	//echo "HELLO WORLD!";

	$counter = 0;
	do {

		// for unlinking first
		$row_memo_img = $memorandum_class->getMemoImagesInfoById(trim($remove_array[$counter],'"'));

		unlink($row_memo_img->image_path);

		$memorandum_class->removeMemoImg(trim($remove_array[$counter],'"'));

		// for removing

		$counter++;
	}while($remove_array_count > $counter);

	$_SESSION["update_memo_success"] = "<center><span class='glyphicon glyphicon-ok' style='color:#1d8348'></span> Memo images is successfully updated is successfully updated.</center>";
	//header("Location:../memorandum.php");


}
else {
	header("Location:..index.php");
}
?>