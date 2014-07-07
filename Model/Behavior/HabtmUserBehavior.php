<?php
class HabtmUserBehavior extends ModelBehavior {
	public $name = 'HabtmUser';

	public function beforeFind(Model $Model, $query = []) {
		if (!empty($query['userId'])) {
			if (empty($Model->hasAndBelongsToMany['User'])) {
				throw new Exception('Cannot use HabtmUserBehavior without first linking Model to User');
			}

			$habtm = $Model->hasAndBelongsToMany['User'];
			if (!empty($habtm['joinTable'])) {
				$table = $habtm['joinTable'];
			} else if (!empty($habtm['with'])) {
				$With = ClassRegistry::init($habtm['with']);
				$table = $With->getDataSource()->fullTableName($With);
			} else {
				$table = $Model->tablePrefix . implode('_', sort(Inflector::tableize($Model->name), 'users'));
			}

			if (!empty($habtm['foreignKey'])) {
				$foreignKey = $habtm['foreignKey'];
			} else {
				$foreignKey = Inflector::underscore($Model->name) . '_id';
			}

			$query['joins'][] = [
				'table' => $table,
				'alias' => 'HabtmUserFilter',
				'conditions' => 'HabtmUserFilter.' . $foreignKey . ' = ' . $Model->escapeField()
			];
			$query['conditions']['HabtmUserFilter.user_id'] = $query['userId'];
			unset($query['userId']);
			return $query;
		}
		return parent::beforeFind($Model, $query);
	}
}