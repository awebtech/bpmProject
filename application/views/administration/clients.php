<?php 

  // Set page title and set crumbs to index
  set_page_title(lang('clients'));
  
  if(owner_company()->canAddClient(logged_user())) {
    add_page_action(lang('add company'), get_url('company', 'add_client'), 'ico-add');
  } // if

?>


<div class="adminClients" style="height:100%;background-color:white">
  <div class="adminHeader">
  	<div class="adminTitle"><?php echo lang('clients') ?></div>
  </div>
  <div class="adminSeparator"></div>
  <div class="adminMainBlock">

<?php if(isset($clients) && is_array($clients) && count($clients)) { ?>
<table style="min-width:400px;margin-top:10px;">
  <tr>
    <th><?php echo lang('name') ?></th>
    <th><?php echo lang('users') ?></th>
    <th><?php echo lang('options') ?></th>
  </tr>
<?php 
	$isAlt = true;
foreach($clients as $client) { 
	$isAlt = !$isAlt;?>
  <tr class="<?php echo $isAlt? 'altRow' : ''?>">
    <td><a class="internalLink" href="<?php echo $client->getViewUrl() ?>"><?php echo clean($client->getName()) ?></a></td>
    <td style="text-align: center"><?php echo $client->countUsers() ?></td>
<?php 
  $options = array(); 
  if($client->canAddUser(logged_user())) {
    $options[] = '<a class="internalLink" href="' . $client->getAddUserUrl() . '">' . lang('add user') . '</a>';
  } // if
  if($client->canUpdatePermissions(logged_user())) {
    $options[] = '<a class="internalLink" href="' . $client->getUpdatePermissionsUrl() . '">' . lang('permissions') . '</a>';
  } // if
  if($client->canEdit(logged_user())) {
    $options[] = '<a class="internalLink" href="' . $client->getEditUrl() . '">' . lang('edit') . '</a>';
  } // if
  if($client->canDelete(logged_user())) {
    $options[] = '<a class="internalLink" href="' . $client->getDeleteClientUrl() . '" onclick="return confirm(\'' . escape_single_quotes(lang('confirm delete client')) . '\')">' . lang('delete') . '</a>';
  } // if
?>
    <td style="font-size:80%;"><?php echo implode(' | ', $options) ?></td>
  </tr>
<?php } // foreach ?>
</table>
<?php } else { ?>
<?php echo lang('no clients in company') ?>
<?php } // if ?>
</div>
</div>