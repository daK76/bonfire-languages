<div class="view split-view">
	<!-- List -->
	<div class="view">
	
		<h2 class="panel-title">Language Files</h2>
		
		<div class="scrollable">
			<div class="list-view" id="role-list">
				<?php if (isset($lang_files) && is_array($lang_files) && count($lang_files)) :?>
					
					<?php foreach ($lang_files as $filename) : ?>
						<div class="list-item" data-id="<?php echo $filename ?>">
							<p><b><?php echo ucfirst(str_replace('_lang.php', '', $filename)) ?></b></p>
						</div>
					<?php endforeach; ?>
				
				<?php else: ?>
					<div class="notification information">
						<p>No Language Files Found.</p>
					</div>
				<?php endif; ?>
			</div>	
		</div>
	</div>
	
	<!-- File Editor -->
	<div id="content" class="view">
		<div id="ajax-content">
			<pre>
			<?php print_r($lang_files); ?>
			</pre>
		</div>	<!-- /ajax-content -->
	</div>	<!-- /content -->
</div>	<!-- /view wrapper -->


<script>
head.ready(function(){
	
	$.subscribe('list-view/list-item/click', function(id){
		$('#content').load('<?php echo current_url() ?>/edit/'+ id, function(response, status, xhr){
		if (status != 'error')
		{
			// Reload our ajaxify for the new pages
			$('.ajaxify').ajaxify({
				target: '#content'
			});
		}
	});
	});
	
});
</script>