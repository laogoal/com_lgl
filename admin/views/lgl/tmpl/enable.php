<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<fieldset>
	<legend>Automatic Updates are not enabled for this copy of LaoGoaL</legend>
<?php if ($this->model->isLocalhost()) :?>
	<p>
		Automatic updates can not be enabled on sites installed on localhost
		or sites not accessible from Internet
	</p>
<?php else :?>
	<p>
		If you are subscribed for automatic updates, all you need is
		a couple of clicks and your site will be connected to data provider
	</p>
	<div>
		<a href="#" class="btn btn-primary" onclick="runF(this); return false;">Enable automatic updates</a>
	</div>
	<div id='enable-container'></div>

	<script type='text/javascript'>
		var runF = function (btn) {
			$(btn).setStyle('display', 'none');
			var installer = new window.LGLInstaller('<?php echo  JUri::base()?>', $('enable-container'));
			installer.addAction({
				name: 'ping',
				text: 'Checking connection to data provider',
				url: 'index.php?option=com_lgl&task=enable&cmd=ping',
				onFailure: function(response) {
					if (null != response && null != response.error) {
						var blaimText = response.error;
						installer.container.innerHTML += '<p class="blaim">' + blaimText + '</p>';
					}
				}
			});
			installer.addAction({
				name: 'sync',
				text: 'Trying to synchronize LaoGoaL Core component with data provider',
				url: 'index.php?option=com_lgl&task=enable&cmd=sync'
			});
			installer.addAction({
				name: 'status',
				text: 'Finishing',
				url: 'index.php?option=com_lgl&task=enable&cmd=status',
				onSuccess: function() {
					installer.container.innerHTML += '<p>Your site is connected to data provider</p>';
					var reloadBtn = new Element('a', {
							class: 'btn btn-primary'}
					);
					reloadBtn.innerHTML = 'Click here to complete action and reload the page';
					reloadBtn.addEvent('click', function(){
						window.location.reload();
					});
					installer.container.grab(
						reloadBtn
					);

				}
			});
			installer.runNext();
		};
	</script>	
<?php endif ?>
</fieldset>
