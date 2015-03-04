<fieldset>
	<legend>LaoGoaL Core component is not initialized on your site</legend>
	<p>
		Do not panic!
		It is necessary to perform some additional steps to make all LaoGoaL extensions work properly.
	</p>
	<div>
		<a href="#" class="btn btn-primary" onclick="runF(this); return false;">Start initialization process</a>
	</div>
	<div id='init-container'></div>
</fieldset>


<script type='text/javascript'>
	var runF = function (btn) {
		$(btn).setStyle('display', 'none');
		var installer = new window.LGLInstaller('<?php echo  JUri::base()?>', $('init-container'));

		var finishAction = {
			name: 'setStatus',
			text: 'Finishing initialization',
			url: 'index.php?option=com_lgl&task=init&cmd=setStatus',
			onSuccess: function() {
				var text = this.succeedLeaguesCount + ' leagues has been loaded from init files';
				installer.container.innerHTML += '<p class="congrat">' + text + '</p>';

				var reloadBtn = new Element('a', {
						class: 'btn btn-primary'}
				);
				reloadBtn.innerHTML = 'Click here to complete initialization and reload the page';
				reloadBtn.addEvent('click', function(){
					window.location.reload();
				});
				installer.container.grab(
					reloadBtn
				);
			}
		};

		installer.addAction({
			name: 'getLeagues',
			text: 'Reading info from LaoGoaL leagues',
			url: 'index.php?option=com_lgl&task=init&cmd=getLeagues',
			onSuccess: function (response) {
				if ('ok' == response.status) {
					installer.findPlaceholder('getLeagues').grab(
						new Element(
							'div',
							{
								id: 'leagues-init-container'
							}
						)
					);
					var leaguesInstaller = new window.LGLInstaller(
						'<?php echo  JUri::base()?>',
						$('leagues-init-container')
					);
					finishAction.succeedLeaguesCount = 0;
					leaguesInstaller.onComplete = function () {
						if (finishAction.succeedLeaguesCount) {
							installer.addAction(finishAction);
							installer.runNext();
						} else {
							var blaimText = 'Initialization failed due to no leagues content was loaded from files';
							installer.container.innerHTML += '<p class="blaim">' + blaimText + '</p>';
						}
					};
					if (response.data.length > 0) {
						for (var i = 0; i < response.data.length; i++) {
							var leagueId = response.data[i];
							leaguesInstaller.addAction({
								name: leagueId,
								text: 'Injecting ' + leagueId,
								url: 'index.php?option=com_lgl&task=init&cmd=injectLeague&params[]=' + leagueId,
								breakOnFailure: false,
								displayOnCreate: true,
								onSuccess: function () {
									finishAction.succeedLeaguesCount++;
								}
							});
						}
					} else {
						installer.addAction(finishAction);
						installer.runNext();
					}
					leaguesInstaller.runNext();
				}
			}
		});
		installer.runNext();
	};
</script>
