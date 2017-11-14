<?php

namespace App\Console\Commands;

use App\FetchedPost;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class FetcherXing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetcher:xing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Content fetcher for xing.al blog';

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
        $data = [];

        $dom = HtmlDomParser::file_get_html("http://www.xing.al/meshume.php");
        $elems = $dom->find("li");
        foreach ($elems as $element) {
            if ($element->class == 'ads adsnew') {
                continue;
            }

            $links = $element->find('a');
            $link = (count($links)) ? $links[0]->href : null;

            $imgs = $element->find('img');
            $image = (count($imgs)) ? $imgs[0]->src : null;

            $categs = $element->find('.a-cat');
            $category = (count($categs)) ? trim($categs[0]->innertext) : null;

            $categoryMapped = $this->getMappedCategory($category);

            $tits = $element->find('.a-tit');
            $title = (count($tits)) ? $tits[0]->innertext : null;

            $linkParts = explode("/", ltrim($link, '/'));
            $id = (isset($linkParts[1]) && is_numeric($linkParts[1])) ? $linkParts[1] : null;

            $data[] = (object)[
                "id" => $id,
                "title" => $title,
                "category" => $category,
                "category_mapped" => $categoryMapped,
                "image_url" => $image,
                "link" => $link,
            ];
        }


        foreach ($data as &$element) {

            $dom = HtmlDomParser::file_get_html("http://www.xing.al/ainc.php?id={$element->id}");

            $times = $dom->find('.a-time');
            $timeCateg = (count($times)) ? $times[0]->innertext : null;
            $timeDom = HtmlDomParser::str_get_html($timeCateg);
            $timeDom->find('span')[0]->outertext = "";
            $time = (string)$timeDom;

            $element->published_at = $time;

            $txts = $dom->find('.a-text');
            $text = (count($txts)) ? $txts[0]->innertext : null;

            $textDome = HtmlDomParser::str_get_html($text);

            // Remove the first image which is the same with the main image
            $textDome->find('iframe')[0]->outertext = "";

            foreach ($textDome->find('iframe') as &$iframe) {
                $iframe->outertext = "";
            }
            $content = (string)$textDome;

            $element->content = $content;

            $html = new \Html2Text\Html2Text($content);
            $element->small_content = str_limit($html->getText(), 200);
        }

        foreach ($data as $article) {

            $exist = FetchedPost::where('fid', $article->id)->first();

            if ($exist) {
                continue;
            }

            FetchedPost::create([
                'fid' => $article->id,
                'title' => $article->title,
                'small_content' => $article->small_content,
                'content' => $article->content,
                'image_url' => $article->image_url,
                'category' => $article->category,
                'category_mapped' => $article->category_mapped,
                'published_at' => $article->published_at,
                'blog_url' => "http://xing.al"
            ]);
        }
    }

    private function getMappedCategory($category)
    {

        $map = [
            "Showbiz shqiptar" => "Showbiz",
            "Showbiz nga Bota" => "Showbiz",
            "Lajmet e fundit" => "Aktualitet",
            "Sociale" => "Aktualitet",
            "Evente" => "Aktualitet",
            "Lifestyle" => "Lifestyle",
            "Ushqimi" => "Lifestyle",
            "Shëndeti" => "Lifestyle",
            "Bukuri & Modë" => "Lifestyle",
            "Marrëdhënie" => "Lifestyle",
            "Funny" => "Funny",
        ];

        return (isset($map[$category])) ? $map[$category] : null;
    }
}
