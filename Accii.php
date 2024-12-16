<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "slider".
 *
 * @property int $id
 * @property string|null $image
 * @property string|null $h1
 * @property string|null $h2
 * @property string|null $text
 * @property string|null $btn
 * @property string|null $url
 * @property string|null $start_at
 * @property string|null $finish_at
 * @property string|null $title
 * @property int|null $sort
 * @property int|null $published
 */
class Accii extends \yii\db\ActiveRecord
{
   

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['title'], 'required'],
            [['start_at', 'finish_at'], 'safe'],
            [['sort', 'published'], 'integer'],
            [['image', 'h1', 'h2', 'btn', 'url', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Изображение',
            'h1' => 'Заголовок большой',
            'h2' => 'Заголовок малый',
            'text' => 'Текст',
            'btn' => 'Текст на кнопке',
            'url' => 'URL',
            'start_at' => 'Дата публикации',
            'finish_at' => 'Дата завершения',
            'sort' => 'Порядок',
            'published' => 'Публикация',
            'title' => 'Наименование',
        ];
    }

   
}
