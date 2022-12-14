<?php

namespace wodrow\yii2wsoftdelete;

use yii\behaviors\TimestampBehavior;

/**
 * Class SoftDeleteBehavior
 *
 * ```php
 * use wodrow\yii2wsoftdelete\behaviors\SoftDeleteBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         SoftDeleteBehavior::className(),
 *     ];
 * }
 * ```
 *
 * @package wodrow\yii2wsoftdelete
 */
class SoftDeleteBehavior extends TimestampBehavior
{
    const EVENT_BEFORE_SOFT_DELETE = 'beforeSoftDelete';
    const EVENT_AFTER_SOFT_DELETE = 'afterSoftDelete';
    const EVENT_BEFORE_FORCE_DELETE = 'beforeForceDelete';
    const EVENT_AFTER_FORCE_DELETE = 'beforeForceDelete';
    const EVENT_BEFORE_RESTORE = 'beforeRestore';
    const EVENT_AFTER_RESTORE = 'afterRestore';

    public $deletedAtAttribute = 'deleted_at';

    public $withTimestamp = false;

    public function init()
    {
        if ($this->withTimestamp) {
            parent::init();
        }
        if ($this->deletedAtAttribute) {
            $this->attributes = array_merge($this->attributes, [
                static::EVENT_BEFORE_SOFT_DELETE => $this->deletedAtAttribute,
            ]);
        }
    }
}