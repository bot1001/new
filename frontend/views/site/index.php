<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '裕家人';

?>
<div id="index">
	<?php echo '<pre>'; 
	if(isset($_SESSION['house'])){
		print_r($_SESSION['house']);
	}
	 ?>
</div>