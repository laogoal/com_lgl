<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


class AcknowledgeCmd extends LGLCmd {

	protected function _execute() {
		try {
			LGLDataHelper::pullUpdates();
		} catch (Exception $x) {
			throw new CmdControllerException($x->getMessage());
		}
	}
}