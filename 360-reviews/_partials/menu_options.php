<div style='width: 90%;'>
    <h2>Listen 360 Reviews</h2>
    <form action="options.php" method="post">
        <?php settings_fields('plugin_options'); ?>
        <?php do_settings_sections('plugin'); ?>

        <p><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
        <p class="description">Use the <span style="font-weight: bold;">[l360_reviews]</span> shortcode on any page to display the reviews. Enjoy!</p>
    </form>
</div>