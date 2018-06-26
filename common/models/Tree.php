<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_tree".
 *
 * @property int $id Unique tree node identifier
 * @property int $root Tree root identifier
 * @property int $lft Nested set left property
 * @property int $rgt Nested set right property
 * @property int $lvl Nested set level / depth
 * @property string $name The tree node name / label
 * @property string $icon The icon to use for the node
 * @property int $icon_type Icon Type: 1 = CSS Class, 2 = Raw Markup
 * @property int $active Whether the node is active (will be set to false on deletion)
 * @property int $selected Whether the node is selected/checked by default
 * @property int $disabled Whether the node is enabled
 * @property int $readonly Whether the node is read only (unlike disabled - will allow toolbar actions)
 * @property int $visible Whether the node is visible
 * @property int $collapsed Whether the node is collapsed by default
 * @property int $movable_u Whether the node is movable one position up
 * @property int $movable_d Whether the node is movable one position down
 * @property int $movable_l Whether the node is movable to the left (from sibling to parent)
 * @property int $movable_r Whether the node is movable to the right (from sibling to child)
 * @property int $removable Whether the node is removable (any children below will be moved as siblings before deletion)
 * @property int $removable_all Whether the node is removable along with descendants
 */
class Tree extends \kartik\tree\models\Tree
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_tree';
    }    
}
