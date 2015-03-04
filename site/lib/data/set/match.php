<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


class LGLDataSetMatch extends LGLDataSet {

	/**
	 * @param array $item
	 * @return JData|LGLDataItemMatch
	 */
	protected function transform(array $item) {
		return new LGLDataItemMatch($item);
	}

}

