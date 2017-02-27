<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $name
 * @property integer $frequency
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '标签名',
            'frequency' => '次数',
        ];
    }

    /**
     * preg_split()使用的一个例子：
     * $tags = "1,2,3,4,5";
     * 通过如下的 return 这里得到的结果是：
     * Array
     * (
     *    [0] => 1
     *    [1] => 2
     *    [2] => 3
     *    [3] => 4
     * )
     *
     *
     * implode 使用的一个例子：
     * $test = ['1', '2' ,'3' ,'4'];
     * $res = implode(',', $test);
     * echo "<pre>".print_r($res,1)."</pre>";
     * 输出结果：
     * 1,2,3,4
     */

    /**
     * 使用正则表达式匹配一个标签数组，preg_split()通过一个正则表达式分隔一个字符串，-1是不限制，后一个是取分隔后的非空部分
     * @param $tags
     * @return array
     */
    public static function string2array($tags)
    {
        return preg_split('/\s*,\s*/', trim($tags), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * 将这个标签数组分割成字符串
     * @param $tags
     * @return string
     */
    Public static function array2string($tags)
    {
        return implode(', ', $tags);
    }

    /**
     * 添加文章标签的操作，有记录加1，无记录加一条记录
     * @param $tags
     */
    public static function addTags($tags)
    {
        if (empty($tags)) return;

        foreach ($tags as $name) {
            $aTag = Tag::find()->where(['name' => $name])->one();
            //zhi($aTag);exit;
            $aTagCount = Tag::find()->where(['name' => $name])->count();

            if (!$aTagCount) {
                $tag = new Tag();
                $tag->name = $name;
                $tag->frequency = 1;
                $tag->save();
            } else {
                $aTag->frequency += 1;
                $aTag->save();
            }
        }
    }

    /**
     * 检查标签并删除标签，删除这条标签的时候，如果数据库中的数据是一条，那么删除这条记录，如果是多条，则-1
     * @param $tags
     */
    public static function removeTags($tags)
    {
        if (empty($tags)) return;

        foreach ($tags as $tag_name) {
            $tag = Tag::find()->where(['name' => $tag_name])->one();
            $tag_count = Tag::find()->where(['name' => $tag_name])->count();

            if ($tag_count) {
                if ($tag_count && $tag->frequency <= 1) {
                    $tag->delete();
                } else {
                    $tag->frequency -= 1;
                    $tag->save();
                }
            }
        }
    }

    /**
     * 更新标签的数量，删除不是增加
     * array_diff() 求两个数组的差集，array_values()返回数组中的所有值
     * @param $oldTags
     * @param $newTags
     */
    public static function updateFrequency($oldTags, $newTags)
    {
        if (!empty($oldTags) || !empty($newTags)) {
            $oldTagsArray = self::string2array($oldTags);
            $newTagsArray = self::string2array($newTags);

            self::addTags(array_values(array_diff($newTagsArray, $oldTagsArray)));
            self::removeTags(array_values(array_diff($oldTagsArray, $newTagsArray)));
        }
    }
}
