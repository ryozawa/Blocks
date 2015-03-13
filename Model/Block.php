<?php
/**
 * Block Model
 *
 * @property Language $Language
 * @property Room $Room
 * @property Frame $Frame
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlocksAppModel', 'Blocks.Model');

/**
 * Block Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Blocks\Model
 */
class Block extends BlocksAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'language_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'room_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Language' => array(
			'className' => 'Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Room' => array(
			'className' => 'Rooms.Room',
			'foreignKey' => 'room_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Frame' => array(
			'className' => 'Frames.Frame',
			'foreignKey' => 'block_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'name' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('Blocks', 'name')),
					'required' => false,
				),
			),
			'public_type' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
				'inList' => array(
					'rule' => array('inList', array('0', '1', '2')),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'from' => array(
				'datetime' => array(
					'rule' => array('datetime', 'ymd'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
			'to' => array(
				'datetime' => array(
					'rule' => array('datetime', 'ymd'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
				'custom' => array(
					'rule' => array('checkFromTo', 'Block'),
					'message' => '開始日より大きい日付を入力してください。',
				),
			),
		));

		return parent::beforeValidate($options);
	}

	public function checkFromTo($target, $modelName) {
		$from = $this->data[$modelName]['from'];
		$to = $this->data[$modelName]['to'];
		if ($from <= $to) {
			return true;
		}
		return false;
	}

/**
 * before save
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 */
	public function beforeSave($options = array()) {
		if (! isset($this->data[$this->name]['id']) && ! isset($this->data[$this->name]['key'])) {
			$this->data[$this->name]['key'] = Security::hash($this->name . mt_rand() . microtime());
		}
		return true;
	}

/**
 * save block
 *
 * @param int $frameId frames.id
 * @param bool|array $validate Either a boolean, or an array.
 *   If a boolean, indicates whether or not to validate before saving.
 *   If an array, can have following keys:
 *
 *   - validate: Set to true/false to enable or disable validation.
 *   - fieldList: An array of fields you want to allow for saving.
 *   - callbacks: Set to false to disable callbacks. Using 'before' or 'after'
 *      will enable only those callbacks.
 *   - `counterCache`: Boolean to control updating of counter caches (if any)
 * @return mixed On success Model::$data if its not empty or true, false on failure
 *
 * @throws InternalErrorException
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */

	public function saveByFrameId($frameId, $validate = true) {
		$this->loadModels([
			'Block' => 'Blocks.Block',
			'Frame' => 'Frames.Frame',
		]);

		//frameの取得
		$frame = $this->Frame->findById($frameId);
		if (! $frame) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		if (isset($frame['Frame']['block_id'])) {
			return $this->findById(is_int($frame['Frame']['block_id']) ? (int)$frame['Frame']['block_id'] : $frame['Frame']['block_id']);
		}

		//blocksテーブル登録
		$block = array();
		$block['Block']['room_id'] = $frame['Frame']['room_id'];
		$block['Block']['language_id'] = $frame['Frame']['language_id'];
		$block = $this->save($block, $validate);
		if (! $block) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
		$blockId = (int)$block['Block']['id'];

		//framesテーブル更新
		$frame['Frame']['block_id'] = $blockId;
		if (! $this->Frame->save($frame, $validate)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return $block;
	}

	public function getBlocksByFrame($roomId, $pluginKey){
		$options = array(
			'recursive' => -1,
			'conditions' => array(
				'room_id' => $roomId,
				'plugin_key' => $pluginKey,
			));

		return $this->find('all', $options);
	}

	public function getEditBlock($blockId, $roomId, $pluginKey){
		$options = array(
			'recursive' => -1,
			'conditions' => array(
				'id' => $blockId,
				'room_id' => $roomId,
				'plugin_key' => $pluginKey,
			));
		$block =  $this->find('first', $options);

		if (! $block) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$format = 'Y/m/d H:i';
		$block['Block']['from'] = $this->__formatStrDate($block['Block']['from'], $format);
		$block['Block']['to'] = $this->__formatStrDate($block['Block']['to'], $format);
		return $block;
	}

	public function saveBlock ($data, $frame) {
		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		try {
			if ($data['Block']['public_type'] !== '2') {
				unset($data['Block']['from']);
				unset($data['Block']['to']);
			}

			// バリデーション
			if (! $this->__validateBlock($data)) {
				return false;
			}

			if (empty($data['Block']['id'])) {
				$this->data['Block']['language_id'] = $frame['Frame']['language_id'];
				$this->data['Block']['room_id'] = $frame['Frame']['room_id'];
				$this->data['Block']['plugin_key'] = $frame['Frame']['plugin_key'];
				$this->data['Block']['key'] = Security::hash('block' . mt_rand() . microtime(), 'md5');
			}

			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			$dataSource->commit();
		} catch (Exception $ex) {
			$dataSource->rollback();
			CakeLog::error($ex);
			throw $ex;
		}
	}

	private function __formatStrDate ($str, $format) {
		$timestamp = strtotime($str);
		if ($timestamp === false) {
			return null;
		}
		return date($format, $timestamp);
	}

/**
 * validate block
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function __validateBlock($data) {
		$this->set($data);
		$this->validates();
		return $this->validationErrors ? false : true;
	}
}
