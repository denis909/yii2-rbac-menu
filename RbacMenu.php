<?php
/**
 * @link http://www.denis909.spb.ru
 * @copyright Copyright (c) 2018 denis909
 * @license LICENSE.md
 */

namespace denis909\yii;

use Yii;

class RbacMenu
{
	
	public $defaultRoles = [];
	
	public $userComponent = 'user';
	
	public static function checkAccess($item, $defaultRoles = [], $userComponent = 'user')
	{
		if (isset($item['roles']))
		{
			$roles = $item['roles'];
		}
		else
		{
			$roles = $defaultRoles;
		}
		
		if ((!is_array($roles)) || (count($roles) == 0))
		{
			return true;
		}
		
		if (Yii::$app->{$userComponent}->isGuest)
		{
			return false;
		}

		foreach($roles as $role)
		{
			if (Yii::$app->{$userComponent}->can($role))
			{
				return true;
			}
		}

		return false;
	}
	
	protected function normalizeItems($items, &$active)
    {
		$return = parent::normailzeItems($items, $active);
		
		foreach($return as $i => $item)
		{
			if (!static::checkAccess($item, $this->defaultRoles, $this->userComponent))
			{
				unset($return[$i]);
			}
		}
		
		return $return;
    }

}