<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



class AcknowledgeCmd extends LGLCmd {

	protected function _execute() {
		try {
			LGLDataHelper::pullUpdates();
		} catch (Exception $x) {
			throw new CmdControllerException($x->getMessage());
		}
	}
}