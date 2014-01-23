<input class='colorpicker<?php echo $inputID; ?>' />
<input type="hidden" class="colorpicker_hidden" name="<?php echo $inputID; ?>" value="<?php echo $inputValue; ?>"/>
<?php 
if($inputValue == ""){
	$defaultColor = "#f00";
} else {
	$defaultColor = $inputValue;
}
?>
<script>
	$(document).ready(function(){
	    $(".colorpicker<?php echo $inputID; ?>").spectrum({
	        color: "<?php echo $defaultColor; ?>",
			preferredFormat: "hex",
			showInput: true,
			showInitial: true,
			showPalette: true,
			showSelectionPalette: true,
			palette: [<?php echo $paletteSelection; ?>],
			localStorageKey: "spectrum.admin<?php echo $post->ID; ?>",
	        change: function(color){
	        	$(this).siblings('.colorpicker_hidden').val(color.toHexString());
	        }
	    });
	});
</script>