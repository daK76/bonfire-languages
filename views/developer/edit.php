<p><b>Editing: <?php echo $file ?></b></p>

<?php echo form_open(current_url(), 'class="ajax-form"'); ?>
	<?php foreach ($english_array as $name => $phrase) :?>
		<div>
			<input type="text" name="<?php echo $name ?>" value="<?php echo isset($lang_array[$name]) ? $lang_array[$name] : set_value($name, $english_array[$name]); ?>"/>
			<p class="small"><?php echo htmlentities($phrase) ?></p>
		</div>
	<?php endforeach; ?>

	<div class="submits">
		<input type="submit" name="submit" value="Save Translation" />
	</div>
	
<?php echo form_close(); ?>