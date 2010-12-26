<?php 

  // Set page title and set crumbs to index
  set_page_title(lang('task templates'));
  
//  if(owner_company()->canAddClient(logged_user())) {
//    add_page_action(lang('add client'), get_url('company', 'add_client'), 'ico-add');
//  } // if

?>


<div class="adminClients" style="height:100%;background-color:white">
  <div class="adminHeader">
  	<div class="adminTitle"><?php echo lang('task templates') ?></div>
  </div>
  <div class="adminSeparator"></div>
  <div class="adminMainBlock">

<?php if(isset($task_templates) && is_array($task_templates) && count($task_templates)) { ?>
<table style="min-width:400px;margin-top:10px;">
  <tr>
    <th><?php echo lang('template') ?></th>
    <th><?php echo lang('workspaces') ?></th>
    <th><?php echo lang('options') ?></th>
  </tr>
<?php 
	$isAlt = true;
foreach($task_templates as $task_template) { 
	$isAlt = !$isAlt; ?>
  <tr class="<?php echo $isAlt? 'altRow' : ''?>">
    <td><a class="internalLink" href="<?php echo $task_template->getViewUrl() ?>"><?php echo clean($task_template->getTitle()) ?></a></td>
    <td style="text-align: center"><?php echo 'XXX' ?></td>
<?php 
  $options = array(); 
  if(can_manage_workspaces(logged_user())) {
    $options[] = '<a class="internalLink" href="' . $task_template->getAssignTemplateToWSUrl() . '">' . lang('assign to workspace') . '</a>';
  } // if
  if($task_template->canDelete(logged_user())) {
  	$options[] = '<a class="internalLink" href="' . $task_template->getDeleteUrl() . '" onclick="return confirm(\'' . escape_single_quotes(lang('confirm delete task template')) . '\')">' . lang('delete template') . '</a>';
  }
?>
    <td style="font-size:80%;"><?php echo implode(' | ', $options) ?></td>
  </tr>
<?php } // foreach ?>
</table>
<?php } else { ?>
<?php echo lang('no task templates') ?>
<?php } // if ?>
</div>
</div>