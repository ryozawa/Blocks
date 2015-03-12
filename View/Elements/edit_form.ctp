<?php echo $this->Form->hidden('Block.id',
	array(
		'value' => $block['id'],
	)); ?>

<div class="form-group">
	<?php echo $this->Form->input('Block.name',
		array(
			'type' => 'text',
			'label' => '名称',
			'class' => 'form-control',
			'ng-model' => 'block.name',
		)); ?>
</div>

<div class="form-group">
	<label>公開設定</label><br/>
	<?php echo $this->Form->input('Block.public_type',
		array(
			'type' => 'radio',
			'options' => array('0' => '非公開', '1' => '公開', '2' => '期間限定公開'),
			'div' => false,
			'legend' => false,
			'ng-model' => 'block.publicType',
			'checked' => true,
		)); ?>
	<div class="row" collapse="block.publicType != 2">
		<fieldset>
			<div class="col-md-5 col-sm-5">
				<p class="input-group">
					<?php echo $this->Form->input('Block.from',
						array(
							'type' => 'text',
							'class' => 'form-control',
							'ng-model' => 'block.from',
							'datepicker-popup' => 'yyyy/MM/dd HH:mm',
							'is-open' => 'isFrom',
							'label' => false,
							'div' => false,
						)); ?>
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="showCalendar($event, 'from')">
							<i class="glyphicon glyphicon-calendar"></i>
						</button>
					</span>
				</p>
			</div>
			<div class="col-md-2 col-sm-2 text-center">
			～
			</div>
			<div class="col-md-5 col-sm-5">
				<p class="input-group">
					<?php echo $this->Form->input('Block.to',
						array(
							'type' => 'text',
							'class' => 'form-control',
							'ng-model' => 'block.to',
							'datepicker-popup' => 'yyyy/MM/dd HH:mm',
							'is-open' => 'isTo',
							'label' => false,
							'div' => false,
						)); ?>
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="showCalendar($event, 'to')">
							<i class="glyphicon glyphicon-calendar"></i>
						</button>
					</span>
				</p>
			</div>
		</fieldset>
	</div>
</div>
