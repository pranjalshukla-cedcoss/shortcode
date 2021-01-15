<?php
get_header();
?>
<div class="entry-content" style="background-color:burlywood">
<?php
do_shortcode( '[products cat="cloths" show="10"]' );
?>
</div>

<?php
get_footer();
