<?php
/** @var $model LGLModel */
$model = $this->model;
?>
<div class="lgl-admin row-fluid">
<?php if ($model->isInitialized()) :?>
	<div class="span5">
		<?php include $this->getPath('leagues'); ?>
	</div>
	<div class="span5">
		<?php if ($model->isUpdatesEnabled()) :?>
			<?php include $this->getPath('status'); ?>
		<?php else :?>
			<?php include $this->getPath('enable'); ?>
		<?php endif ?>
	</div>
<?php else :?>
	<div class="span8">
		<?php include $this->getPath('initialize'); ?>
	</div>
<?php endif ?>
</div>
