<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Exception;
use MOLiBot\Services\MOLiBlogArticleService;
use MOLiBot\Services\TelegramService;

class MOLi_Blog_Article extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MOLi:blog-article-check {--dry-run} {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New Article From MOLi Blog（add --dry-run for testing mode）';

    /**
     * @var MOLiBlogArticleService
     */
    private $MOLiBlogArticleService;

    /**
     * @var telegramService
     */
    private $telegramService;

    /**
     * Create a new command instance.
     *
     * @param MOLiBlogArticleService $MOLiBlogArticleService
     * @param TelegramService $telegramService
     *
     * @return void
     */
    public function __construct(MOLiBlogArticleService $MOLiBlogArticleService,
                                TelegramService $telegramService)
    {
        parent::__construct();

        $this->MOLiBlogArticleService = $MOLiBlogArticleService;
        $this->telegramService = $telegramService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws
     */
    public function handle()
    {
        try {
            $printPosts = [];

            $contents = $this->MOLiBlogArticleService->getMOLiBlogArticles(1);

            $totalPages = $contents['meta']['pagination']['pages'];

            $posts = $contents['posts'];

            $postResult = $this->postHandler($posts);

            $printPosts = array_merge($printPosts, $postResult);

            if ($totalPages != 1) {
                for ($i = 2; $i <= $totalPages; $i++) {
                    $moreContents = $this->MOLiBlogArticleService->getMOLiBlogArticles($i);

                    $morePosts = $moreContents['posts'];

                    $morePostResult = $this->postHandler($morePosts);

                    $printPosts = array_merge($printPosts, $morePostResult);
                }
            }

            if ($this->option('dry-run')) {
                $headers = ['id', 'uuid', '文章標題'];

                $this->table($headers, $printPosts);
            } else {
                $this->info('Mission Complete!');
            }

            return 0;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }

    /**
     * @param $posts
     * @return array
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    private function postHandler($posts)
    {
        $printPosts = [];

        foreach ($posts as $post) {
            if ($this->option('dry-run')) {
                $printPosts[] = [
                    'id'   => $post['id'],
                    'uuid' => $post['uuid'],
                    '文章標題' => $post['title']
                ];
            }

            if (!$this->MOLiBlogArticleService->checkArticlePublished($post['id']) && !$this->option('dry-run')) {
                $tags = '';

                foreach ($post['tags'] as $tag) {
                    $tags .= '#' . $tag['name'] . ' ';
                }

                if ($this->option('init')) {
                    $chatId = config('telegram-channel.test');
                } else {
                    $chatId = config('telegram-channel.MOLi');
                }

                $text = 'MOLi Blog 新文快報：' . PHP_EOL .
                    $post['title'] . ' By ' . $post['authors'][0]['name'] . PHP_EOL .
                    $post['url'] . PHP_EOL . PHP_EOL .
                    $tags;

                $this->telegramService->sendMessage(
                    $chatId,
                    $text,
                    null,
                    true
                );

                $this->MOLiBlogArticleService->storePublishedArticle($post);

                sleep(5);
            }
        }

        return $printPosts;
    }
}
