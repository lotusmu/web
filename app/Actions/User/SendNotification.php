<?php

namespace App\Actions\User;

use App\Models\User\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SendNotification
{
    private string $title;

    private string $body;

    private array $bodyParameters = [];

    private array $actions = [];

    /**
     * Initialize with title
     */
    public static function make(string $title): self
    {
        $instance = new self;
        $instance->title = $title;

        return $instance;
    }

    /**
     * Set the notification body
     */
    public function body(string $body, array $bodyParameters = []): self
    {
        $this->body = $body;
        $this->bodyParameters = $bodyParameters;

        return $this;
    }

    /**
     * Add an action to the notification
     */
    public function action(string $label, string $url): self
    {
        $this->actions[] = [
            'label' => $label,
            'url' => $url,
        ];

        return $this;
    }

    /**
     * Get the notification data array
     */
    private function getNotificationData(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'body_parameters' => $this->bodyParameters,
            'actions' => $this->actions,
        ];
    }

    /**
     * Send the notification to the user
     */
    public function send(User $user): DatabaseNotification
    {
        return $user->notifications()->create([
            'id' => Str::uuid()->toString(),
            'type' => 'UserNotification',
            'data' => $this->getNotificationData(),
        ]);
    }

    /**
     * Send the notification to all admin users
     */
    public function sendToAdmins(): Collection
    {
        return User::where('is_admin', true)
            ->get()
            ->map(fn ($admin) => $this->send($admin));
    }
}
