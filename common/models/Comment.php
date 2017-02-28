<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $content
 * @property integer $status
 * @property integer $created_at
 * @property integer $user_id
 * @property string $email
 * @property string $url
 * @property integer $post_id
 *
 * @property Post $post
 * @property Commentstatus $status0
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['status', 'created_at', 'user_id', 'post_id'], 'integer'],
            [['email', 'url'], 'string', 'max' => 100],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Commentstatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'content' => '内容',
            'status' => '状态',
            'created_at' => '创建时间',
            'user_id' => '用户名',
            'email' => '电子邮箱',
            'url' => '访问地址',
            'post_id' => '评论文章',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Commentstatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string 获取文章评论的长度
     */
    public function getBeginning()
    {
        $tmpStr = strip_tags($this->content);
        $tmpLen = mb_strlen($tmpStr);

        return mb_substr($tmpStr, 0, 10, "UTF-8") . (($tmpLen > 10) ? '...' : '');
    }

    /**
     * 审核通过设置评论状态为1
     * @return bool
     */
    public function approve()
    {
        $this->status = 1;
        return ($this->save() ? true : false);
    }
}
