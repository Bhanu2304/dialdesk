
<!-- File: /app/View/UserType/index.ctp -->
<h1><?php echo $this->fetch('title'); ?></h1>
<?php echo $this->fetch('content'); ?>
<h1>Blog posts</h1>
<table>
<tr>
<th>Id</th>
<th>User Type</th>
<th>Privalges</th>
</tr>
<!-- Here is where we loop through our $posts array, printing out post info -->
<?php foreach ($user_type as $post): ?>
<tr>
<td><?php echo $post['UserType']['id']; ?></td>
<td><?php echo $post['UserType']['User_Type']; ?></td>
<td>
<?php echo $this->Html->link($post['UserType']['page_access'],
array('controller' => 'user_type', 'action' => 'view', $post['UserType']['id'])); ?>
</td>
</tr>
<?php endforeach; ?>
<?php unset($post); ?>
</table>