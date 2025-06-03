<?php

namespace App\Actions\User;

use App\Enums\Content\ArticleType;
use App\Models\Content\Article;
use App\Models\User\User;
use Illuminate\Support\Facades\Queue;

class SendArticleNotificationAction
{
    public function handle(Article $article): void
    {
        if (! $article->is_published) {
            return;
        }

        $this->dispatchNotifications($article);
    }

    private function dispatchNotifications(Article $article): void
    {
        User::query()
            ->whereNotNull('email_verified_at')
            ->chunk(100, function ($users) use ($article) {
                foreach ($users as $user) {
                    Queue::push(function () use ($article, $user) {
                        $this->sendNotificationToUser($article, $user);
                    });
                }
            });
    }

    private function sendNotificationToUser(Article $article, User $user): void
    {
        $notificationData = $this->getNotificationData($article);

        SendNotification::make($notificationData['title'])
            ->body($notificationData['body'], [
                'title' => $article->title,
            ])
            ->action('Read More', route('articles.show', $article->slug))
            ->send($user);
    }

    private function getNotificationData(Article $article): array
    {
        return match ($article->type) {
            ArticleType::PATCH_NOTE => [
                'title' => 'New Update Available',
                'body' => 'A new patch note ":title" has been released. Check out the latest improvements and fixes.',
            ],
            default => [
                'title' => 'New Article Published',
                'body' => 'We\'ve published a new article ":title". Don\'t miss out on the latest updates!',
            ],
        };
    }
}
