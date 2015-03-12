<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

/** @var $model LGLModel */
$model = $this->model;
$leagues = $model->getLeaguesInfo();
?>
<fieldset>
	<legend>LaoGoaL Leagues Summary</legend>
<?php if (sizeof($leagues)) :?>
	<table>
		<thead>
		<tr>
			<td><?php echo $this->escape(JText::_('League')) ?></td>
			<td><?php echo $this->escape(JText::_('Summary')) ?></td>
			<td><?php echo $this->escape(JText::_('Last Update Time')) ?></td>
			<td><?php echo $this->escape(JText::_('Updates')) ?></td>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($leagues as $league) :?>
			<tr>
				<td><?php echo $this->escape(JText::_($league['league_id'])) ?></td>
				<td>
					<?php echo $league['matches_count'] ?> matches,
					<?php echo $league['standings_count'] ?> standings rows
				</td>
				<td><?php echo $this->escape(JHtml::date($league['luts'], 'M d, H:i')) ?></td>
				<td>
					<?php if (isset($league['remote']) && $league['remote']) : ?>
						<?php echo $this->escape(JText::_('enabled')) ?>
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
<?php else :?>
	<p>
		LaoGoaL Core component has been loaded and initialized, but no data about leagues found
	</p>
<?php endif ?>
</fieldset>
