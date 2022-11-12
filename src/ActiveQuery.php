<?php

namespace wodrow\yii2wsoftdelete;

use yii\db\Exception;
use yii\db\QueryBuilder;

/**
 * @property $deletedAtAttribute
 */
class ActiveQuery extends \yii\db\ActiveQuery
{
    protected $_deletedAtAttribute;

    public function setDeletedAtAttribute($deletedAtAttribute)
    {
        $this->_deletedAtAttribute = $deletedAtAttribute;
    }

    public function getDeletedAtAttribute()
    {
        return $this->_deletedAtAttribute;
    }
    
    const WITH_TRASHED = 0;
    const WITHOUT_TRASHED = 1;
    const ONLY_TRASHED = 2;
    /**
     * @var int
     */
    private $_trashed;

    /**
     * @param QueryBuilder $builder
     * @return $this|\yii\db\Query
     */
    public function prepare($builder)
    {
        $query = parent::prepare($builder);
        switch ($this->getTrashed()) {
            case static::WITHOUT_TRASHED:
                $query->andWhere(['=', $this->deletedAtAttribute, 0]);
                break;
            case static::ONLY_TRASHED:
                $query->andWhere(['>', $this->deletedAtAttribute, 0]);
                break;
            case static::WITH_TRASHED: // No break;
            default:
                break;
        }

        return $query;
    }

    public function withTrashed()
    {
        if (!$this->deletedAtAttribute) {
            throw new Exception("place set delete at attribute to use withTrashed()");
        }
        $this->_trashed = static::WITH_TRASHED;
        return $this;
    }

    public function withoutTrashed()
    {
        if (!$this->deletedAtAttribute) {
            throw new Exception("place set delete at attribute to use withoutTrashed()");
        }
        $this->_trashed = static::WITHOUT_TRASHED;
        return $this;
    }

    public function onlyTrashed()
    {
        if (!$this->deletedAtAttribute) {
            throw new Exception("place set delete at attribute to use onlyTrashed()");
        }
        $this->_trashed = static::ONLY_TRASHED;

        return $this;
    }

    public function getTrashed()
    {
        if ($this->_trashed === null) {
            $this->_trashed = static::WITHOUT_TRASHED;
        }

        return $this->_trashed;
    }
}