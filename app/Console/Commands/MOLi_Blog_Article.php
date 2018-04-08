<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use Telegram;
use MOLiBot\Published_MOLi_Blog_Article;

class MOLi_Blog_Article extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:check {--dry-run} {--init}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check New Article From MOLi Blog（add --dry-run for testing mode）';

    /**
     * @var Published_MOLi_Blog_Article
     */
    private $Published_MOLi_Blog_ArticleModel;

    /**
     * Create a new command instance.
     *
     * @param Published_MOLi_Blog_Article $Published_MOLi_Blog_ArticleModel
     *
     * @return void
     */
    public function __construct(Published_MOLi_Blog_Article $Published_MOLi_Blog_ArticleModel)
    {
        parent::__construct();

        $this->Published_MOLi_Blog_ArticleModel = $Published_MOLi_Blog_ArticleModel;
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
            $fileContents = $this->getData($limit);

            if (!empty($fileContents)) {
                if (!empty($fileContents->posts)) {
                    $posts = $fileContents->posts;
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

            if ($this->Published_MOLi_Blog_ArticleModel->where('id', $post->id)->exists()) {
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

                    $this->Published_MOLi_Blog_ArticleModel->create([
                        'id' => $post->id,
                        'uuid' => $post->uuid,
                        'title' => $post->title
                    ]);
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

    private function getData($limit = 1)
    {
        $MOLi_blog_api = env('MOLi_BLOG_URL') . '/ghost/api/v0.1/posts/?client_id=' . env('MOLi_BLOG_CLIENT_ID') . '&client_secret=' . env('MOLi_BLOG_CLIENT_SECRET') . '&include=author,tags&limit=' . $limit;

        $client = new GuzzleHttpClient();

        try {
            $fileContents = $client->request('GET', $MOLi_blog_api, [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'Accept' => 'application/json'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            $fileContents = '';
        }

        return $fileContents;
    }
}
