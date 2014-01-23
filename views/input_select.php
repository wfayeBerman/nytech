<select name="<?php echo $inputID; ?>">
	<option value=""></option>
<? foreach ($field_options as $option): ?>
	<option value="<?php echo $option['id']; ?>" <?php if($inputValue == $option['id']){ echo "selected"; }  ?>><?php echo $option['name']; ?></option>
<? endforeach; ?>
</select>