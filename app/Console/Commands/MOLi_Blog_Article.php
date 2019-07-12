<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use MOLiBot\Services\MOLiBlogArticleService;

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
     * Create a new command instance.
     *
     * @param MOLiBlogArticleService $MOLiBlogArticleService
     *
     * @return void
     */
    public function __construct(MOLiBlogArticleService $MOLiBlogArticleService)
    {
        parent::__construct();

        $this->MOLiBlogArticleService = $MOLiBlogArticleService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ( !filter_var(env('MOLi_BLOG_URL'), FILTER_VALIDATE_URL) ) {
            $this->error('MOLi_BLOG_API URL is not valid!');
            return;
        }

        $limit = 1;

        if ($this->option('dry-run')) {
            $headers = ['id', 'uuid', '文章標題'];

            $datas = [];
        }

        while(true) {
            $fileContents = $this->MOLiBlogArticleService->getMOLiBlogArticles($limit);

            if (!empty($fileContents)) {
                if (!empty(json_decode($fileContents->getBody())->{'posts'})) {
                    $posts = json_decode($fileContents->getBody())->{'posts'};
                } else {
                    $this->error('No post!');
                    break;
                }
            } else {
                $this->error('Can\'t Get Data!');
                break;
            }

            $use_post = $limit - 1; // 拿第幾篇 post 來比對

            $post = $posts[$use_post];

            if ($this->MOLiBlogArticleService->checkArticlePublished($post->id)) {
                break;
            } else {
                if ($this->option('dry-run')) {
                    $datas[] = [
                        'id' => $post->id,
                        'uuid' => $post->uuid,
                        '文章標題' => $post->title
                    ];
                } else {
                    $tags = '';

                    foreach ($post->tags as $tag) {
                        $tags .= '#' . $tag->name . ' ';
                    }

                    if ($this->option('init')) {
                        $chat_id = env('TEST_CHANNEL');
                    } else {
                        $chat_id = env('MOLi_CHANNEL');
                    }

                    Telegram::sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'MOLi Blog 新文快報：' . PHP_EOL .
                            $post->title . ' By ' . $post->author->name . PHP_EOL .
                            env('MOLi_BLOG_URL') . $post->url . PHP_EOL . PHP_EOL .
                            $tags
                    ]);

                    $this->MOLiBlogArticleService->storePublishedArticle($post);
                }

                sleep(5);

                $limit++;
            }
        }

        if ($this->option('dry-run')) {
            $this->table($headers, $datas);
        } else {
            $this->info('Mission Complete!');
        }

        return;
    }
}
