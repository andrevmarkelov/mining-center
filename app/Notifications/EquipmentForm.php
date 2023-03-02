<?php

namespace App\Notifications;

use App\Models\DataCenter;
use App\Models\Equipment;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EquipmentForm extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = 'Узнать стоимость оборудования';

        if (empty($this->data['miner_id'])) {
            $message = 'Заявка на помощь подбору оборудования';
        }

        $notifiable = (new MailMessage)->subject($message);

        if (!empty($this->data['miner_id'])) {
            if (request()->input('form_type') == 'data_centers') {
                $link = 'Дата центр: <a href="' . route('data_centers.show', DataCenter::find($this->data['miner_id'])->alias) . '">смотреть</a>';
            } else {
                $link = 'Оборудование: <a href="' . route('equipments.show', Equipment::find($this->data['miner_id'])->alias) . '">смотреть</a>';
            }
            $notifiable->line(new HtmlString($link));
        }

        return $notifiable->line('Имя: ' . $this->data['name'])
            ->line('Email: ' . $this->data['email'])
            ->line('Telegram / Whatsapp: ' . ($this->data['telegram'] ?: '-'))
            ->line('Комментарий: ' . ($this->data['comment'] ?: '-'));
    }
}
