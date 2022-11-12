Yii2 SoftDelete
===============
[![Build Status](https://travis-ci.org/wodrow/yii2wsoftdelete.svg)](https://travis-ci.org/wodrow/yii2wsoftdelete)
[![Latest Stable Version](https://poser.pugx.org/wodrow/yii2wsoftdelete/v/stable.svg)](https://packagist.org/packages/wodrow/yii2wsoftdelete)
[![Total Downloads](https://poser.pugx.org/wodrow/yii2wsoftdelete/downloads.svg)](https://packagist.org/packages/wodrow/yii2wsoftdelete)
[![Latest Unstable Version](https://poser.pugx.org/wodrow/yii2wsoftdelete/v/unstable.svg)](https://packagist.org/packages/wodrow/yii2wsoftdelete)
[![License](https://poser.pugx.org/wodrow/yii2wsoftdelete/license.svg)](https://packagist.org/packages/wodrow/yii2wsoftdelete)

Soft delete extension for Yii2 framework.

This extension ensures that soft-deleted has delete native consistent behavior and is IDE-friendly.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist wodrow/yii2wsoftdelete "^1.0.1"
```

or add

```
"wodrow/yii2wsoftdelete": "^1.0.1"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

Edit model class:
```php
use wodrow\softdelete\SoftDeleteBehavior;
use wodrow\softdelete\SoftDeleteTrait;

class Model extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;

    public static function getDeletedAtAttribute()
    {
        return "deleted_at";
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = ArrayHelper::merge($behaviors, []);
        if (static::getDeletedAtAttribute()) {
            $behaviors = ArrayHelper::merge($behaviors, [
                'soft-delete' => [
                    'class' => SoftDeleteBehavior::class,
                    'deletedAtAttribute' => static::getDeletedAtAttribute(),
                ],
            ]);
        }
        return $behaviors;
    }
}
```

Change database table structures, add `deleted_at (int 11 unsigned)` field and attached to UNIQUE index.

API
---

### ActiveRecord class (SoftDelete Trait):

find系列方法会返回 `wodrow\softdelete\ActiveQuery` 对象。

+ softDelete() 使用软删除模式删除数据
+ forceDelete() 使用物理删除模式强制删除数据
+ restore() 恢复被软删除的模型数据
+ isTrashed() 是否被软删除

以下命令分别是 `find()` / `findOne()` / `findAll()` 在不同模式下的对应版本：

所有模型（包括被软删除的）：

+ findWithTrashed()
+ findOneWithTrashed($condition)
+ findAllWithTrashed($condition)

只查找被软删除的模型：

+ findOnlyTrashed()
+ findOneOnlyTrashed($condition)
+ findAllOnlyTrashed($condition)

以下的命令均被重写成软删除版本：

+ find()
+ findOne()
+ findAll()
+ delete()

### wodrow\softdelete\ActiveQuery

增加了 `withTrashed()`, `withoutTrashed()` 和 `onlyTrashed()` 三个方法，
设置相应的查找模式。