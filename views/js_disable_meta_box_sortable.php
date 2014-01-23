<script type="text/javascript">
jQuery(document).ready( function($) {
    $('#' + '<?php echo $inputID; ?>').parents('.meta-box-sortables').sortable({
        disabled: true
    });
    $('#' + '<?php echo $inputID; ?>').parents('.postbox').css('background-color', '#FFFFFF');
    $('#' + '<?php echo $inputID; ?>').parents('.postbox').css('background-image', 'none');
    $('.postbox .hndle').css('cursor', 'pointer');
});	
</script>