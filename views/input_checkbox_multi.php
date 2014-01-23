<? foreach ($field_options as $option): ?>
	<input type="checkbox" name="<?php echo $inputID; ?>[]" value="<?php echo $option['id']; ?>" <?php if(is_array($inputValue)){if(in_array($option['id'], $inputValue)){ echo "checked"; }}  ?>><?php echo html_entity_decode($option['name']); ?><br />
<? endforeach; ?>