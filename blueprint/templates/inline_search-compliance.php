<?php $compliance_message = wp_kses_post($_POST['compliance_message']); ?>
<?php $compliance_message = wp_parse_args($compliance_message, array('agent_name' => false, 'office_name' => false, 'img' => false, 'disclaimer' => false)); ?>
<div class="clear"></div>
<div class="compliance-wrapper">
	<?php if ($compliance_message['agent_name']): ?>
		<p class="inline-compliance">Listing Agent: <?php echo $compliance_message['agent_name'] ?></p>
	<?php endif ?>
	<?php if ($compliance_message['office_name']): ?>
		<p class="inline-compliance">Courtesy of: <?php echo $compliance_message['office_name'] ?></p>
	<?php endif ?>
	<?php if ($compliance_message['img']): ?>
		<img src="<?php echo $compliance_message['img'] ?>" alt="">
	<?php endif ?>
	<?php if ($compliance_message['disclaimer']): ?>
		<p class="disclaimer"><?php echo $compliance_message['disclaimer'] ?></p>
	<?php endif ?>
</div>