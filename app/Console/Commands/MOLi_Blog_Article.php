<?php

namespace MOLiBot\Console\Commands;

use Illuminate\Console\Command;

use Telegram;
use MOLiBot\Published_MOLi_Blog_Article;

class MOLi_Blog_Article extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:check {--dry-run}';

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
        $MOLi_blog_api = env('MOLi_BLOG_URL') . '/' . 'ghost/api/v0.1/posts/?client_id=' . env('MOLi_BLOG_CLIENT_ID') . '&client_secret=' . env('MOLi_BLOG_CLIENT_SECRET') . '&include=author,tags&limit=all';

        if ( !filter_var($MOLi_blog_api, FILTER_VALIDATE_URL) ) {
            $this->error('MOLi_BLOG_API Data is not valid!');
            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $MOLi_blog_api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["cache-control: no-cache", "user-agent: MOLi Bot"]);
        $fileContents = curl_exec($ch);

        if (curl_errno($ch) == 28) {
            //Log CURL Timeout message
        }

        curl_close($ch);

        if (!empty($fileContents)) {
            $json = json_decode($fileContents);
            $posts = $json->posts;
        } else {
            $this->error('Can\'t Get Data!');
            return;
        }

        if ($this->option('dry-run')) {
            $headers = ['id', 'uuid', '文章標題'];

            $datas = [];

            foreach ($posts as $post) {
                if ( !$this->Published_MOLi_Blog_ArticleModel->where('id', $post->id)->exists() ) {
                    $datas[] = [
                        'id' => $post->id,
                        'uuid' => $post->uuid,
                        '文章標題' => $post->title
                    ];
                }
            }

            $this->table($headers, $datas);
        } else {
            foreach ($posts as $post) {
                if ( !$this->Published_MOLi_Blog_ArticleModel->where('id', $post->id)->exists() ) {
                    $tags = '';

                    foreach ($post->tags as $tag) {
                        $tags .= '#' . $tag->name . ' ';
                    }

                    Telegram::sendMessage([
                        'chat_id' => env('MOLi_CHANNEL'),
                        'text' => 'MOLi Blog 新文快報：' . PHP_EOL .
                                  $post->title . ' By ' . $post->author->name . PHP_EOL .
                                  env('MOLi_BLOG_URL') . '/2017/08/17/journey-of-becoming-a-graduate-student' . PHP_EOL . PHP_EOL .
                                  $tags
                    ]);

                    $this->Published_MOLi_Blog_ArticleModel->create([
                        'id' => $post->id,
                        'uuid' => $post->uuid,
                        'title' => $post->title
                    ]);

                    sleep(5);
                }
            }

            $this->info('Mission Complete!');
        }
    }
}
