<?php if (isset($languages) && is_array($languages)) :?>
<ul>
	<?php foreach ($languages as $language) : ?>
		<li>
			<a href="<?php echo site_url('admin/developer/languages/'. $language) ?>">
				<?php echo ucfirst($language); ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>