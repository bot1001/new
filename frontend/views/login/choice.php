<?php
use yii\ helpers\ Url;
$this->title = '房屋选择';
?>

<style>
	div {
		text-align: center;
		height: 30px;
		font-weight: 600;
	}
</style>
    <?php
    foreach ( $reale as $real ) {
    	echo '
    	   <div class="row">
    	       <div class-log-2 >';
    	?> 
    	<a href = "<?php echo Url::to(['/invoice/index','id' => $real['id']]) ?>" >
    		<?php
    	         $len = strlen( $real[ 'number' ] );
    	         $name = explode( '-', $real[ 'name' ] );
             
    	         echo $real[ 'community_name' ] . ' ' . $real[ 'building_name' ];
             
    	         if ( $len > 2 ) {
    	         	echo '1 单元';
    	         } else {
    	         	echo $real[ 'number' ] . ' ' . '单元';
    	         }
             
    	         echo end( $name );
    	         ?> 
    	</a>
    	<?php
    
    	echo '</div>
    	   </div>';
    }
?>