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
    // public $startDate;
    // public $startTime;
    // public $finishDate;
    // public $finishTime;
    // public $publishStatus;

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

    // public function afterFind()
    // {
    //     $this->startDate = $this->start_at ? date('d.m.Y', strtotime($this->start_at)) : '---';
    //     $this->startTime = $this->start_at ? date('H:i', strtotime($this->start_at)) : '';

    //     $this->finishDate = $this->finish_at ? date('d.m.Y', strtotime($this->finish_at)) : '---';
    //     $this->finishTime = $this->finish_at ? date('H:i', strtotime($this->finish_at)) : '';

    //     if($this->published && $this->start_at && $this->start_at > date('Y-m-d H:i:s', time()))
    //         $this->publishStatus = '<i class="fas fa-clock" style="color: #FD7E14" title="Ожидание публикации"></i>';
    //     elseif($this->published && $this->finish_at && $this->finish_at < date('Y-m-d H:i:s', time()))
    //         $this->publishStatus = '<i class="fas fa-exclamation-triangle" style="color: #aa0000" title="Публикация завершена"></i>';
    //     else {
    //         if($this->published)
    //             $this->publishStatus = '<i class="fa fa-eye prop-on" aria-hidden="true" title="Опубликовано"></i>';
    //         else
    //             $this->publishStatus = '<i class="fa fa-eye-slash prop-off" aria-hidden="true" title="Не опубликовано"></i>';

    //     }

    //     //parent::afterFind(); // TODO: Change the autogenerated stub
    // }
}
