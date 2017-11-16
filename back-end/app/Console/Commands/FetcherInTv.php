<?php

namespace App\Console\Commands;

use App\FetchedPost;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class FetcherInTv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetcher:intv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Content fetcher for intv.al blog';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = \Httpful\Request::get("http://www.intv.al/app/cache/modules/real-time-news/real_time_news.json")->expectsJson()->send();
        $lastArticles = $response->body->normal_articles;

        $lastFetched = FetchedPost::where('blog_url', 'http://www.intv.al')->orderBy('id', 'desc')->first();

        if ($lastFetched) {
            $fid = (int)$lastFetched->fid;
            $fid++;
        } else {
            $fid = 42075;
        }

        $data = [];


        for ($i = 0; $i < 6; $i++) {

            $article = $lastArticles[$i];

            if ($article->id <= $fid) {
                continue;
            }


            $data[] = (object)[
                "id" => $article->id,
                "title" => $article->title,
                "link" => "http://www.intv.al/" . $article->alias . "-" . $article->id,
            ];
        }
        foreach ($data as &$element) {

            try {
                $dom = HtmlDomParser::file_get_html($element->link);

                $imgs = $dom->find('#article-container #article-img');
                $image_url = (count($imgs)) ? $imgs[0]->src : null;

                $element->image_url = preg_replace('/\?.*/', '', $image_url);

                $times = $dom->find('#article-container #article-date');
                $time = (count($times)) ? $times[0]->innertext : null;
                $time = str_replace('Publikuar më:', '', $time);
                $element->published_at = trim($time);

                $txts = $dom->find('#article-container #article-content');
                $fullBody = (count($txts)) ? $txts[0]->innertext : null;

                $fullBody .= "<p>Burimi: <a href='http://intv.al'>intv.al</a></p>";

                $content = trim($fullBody);
                $element->content = $content;

                $html = new \Html2Text\Html2Text($content);
                $small_content = str_limit($html->getText(), 200);
                $element->small_content = $small_content;

            } catch (\Exception $ex) {
                continue;
            }
        }


        foreach ($data as $article) {

            $exist = FetchedPost::where('fid', $article->id)->first();

            if ($exist) {
                continue;
            }

            $this->info("Inserting data to DB...");
            FetchedPost::create([
                'fid' => $article->id,
                'title' => $article->title,
                'small_content' => $article->small_content,
                'content' => $article->content,
                'image_url' => $article->image_url,
                'category' => "News",
                'category_mapped' => "Aktualitet",
                'published_at' => $article->published_at,
                'blog_url' => "http://intv.al"
            ]);
        }
    }
}