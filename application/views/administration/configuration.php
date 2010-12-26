<?php
set_page_title(lang('configuration'));
?>

<div class="adminConfiguration" style="height:100%;background-color:white">
	<div class="adminHeader">
		<div class="adminTitle"><?php echo lang('configuration') ?></div>
	</div>
	<div class="adminSeparator"></div>
	<div class="adminMainBlock">
	<?php if (isset($config_categories) && is_array($config_categories) && count($config_categories)) { ?>
		<?php foreach ($config_categories as $config_category) { ?>
			<?php if (!$config_category->isEmpty()) { ?>
				<div class="configCategory" id="category_<?php echo $config_category->getName() ?>">
					<h2><a class="internalLink" href="<?php echo $config_category->getUpdateUrl() ?>"><?php echo clean($config_category->getDisplayName()) ?></a></h2>
					<?php if (trim($config_category->getDisplayDescription())) { ?>
						<div class="configCategoryDescription"><?php echo nl2br(clean($config_category->getDisplayDescription())) ?></div>
					<?php } // if ?>
				</div>
			<?php } // if ?>
		<?php } // foreach ?>
	<?php } // if ?>
	<div class="configCategory" id="category_default_user_preferences">
			<h2><a class="internalLink" href="<?php echo get_url('config', 'default_user_preferences') ?>"><?php echo lang('default user preferences') ?></a></h2>
			<div class="configCategoryDescription"><?php echo lang('default user preferences desc') ?></div>
		</div>
	</div>
</div>