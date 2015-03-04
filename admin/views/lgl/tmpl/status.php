<?php
/** @var $model LGLModel */
$model = $this->model;
$info = $model->getRemoteInfo();
?>
<fieldset>
	<legend>LaoGoaL Core component status</legend>
	<?php if (isset($info['status'])) :?>
		<dl>
			<dt>Subscription status</dt>
			<dd>
				<?php echo $this->escape(JText::_($info['status'])) ?>
			</dd>
		</dl>
	<?php endif ?>
	<?php if (isset($info['expires'])) :?>
		<dl>
			<dt>Expiration date</dt>
			<dd>
				<?php echo $this->escape(JHTML::date($info['expires'], 'M d, Y H:i')) ?>
			</dd>
		</dl>
	<?php endif ?>
</fieldset>
