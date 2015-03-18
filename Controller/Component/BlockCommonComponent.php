<?php
/**
 * BlockCommon Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * BlockCommon Component
 *
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @package NetCommons\NetCommons\Controller\Component
 */
class BlockCommonComponent extends Component {

/**
 * type private
 *
 * @var string
 */
	const TYPE_PRIVATE = '0';

/**
 * type public
 *
 * @var string
 */
	const TYPE_PUBLIC = '1';

/**
 * type limited public
 *
 * @var string
 */
	const TYPE_LIMITED_PUBLIC = '2';
}