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

        $lastFetched = FetchedPost::where('blog_url', 'http://xing.al')->orderBy('id', 'desc')->first();

        if ($lastFetched) {
            $fid = (int)$lastFetched->fid;
            $fid++;
        } else {
            $fid = 9220;
        }

        $continue = true;
        $increment = 0;
        do {
            $this->info("Checking for fid: " . $fid);

            $increment++;

            if ($increment > 5) {
                break;
            }

            $exist = FetchedPost::where('fid', $fid)->first();

            if ($exist) {
                $fid++;
                continue;
            }

            $dom = HtmlDomParser::file_get_html("http://www.xing.al/ainc.php?id={$fid}");

            $tits = $dom->find('.a-title');
            $title = (count($tits)) ? $tits[0]->innertext : null;

            if ($title == "") {
                $fid++;
            } else {

                $times = $dom->find('.a-time');
                $timeCateg = (count($times)) ? $times[0]->innertext : null;

                $cats = $dom->find('.the-cat');
                $category = (count($cats)) ? $cats[0]->innertext : null;
                $categoryMapped = $this->getMappedCategory($category);

                $timeDom = HtmlDomParser::str_get_html($timeCateg);
                $timeDom->find('span')[0]->outertext = "";

                $published_at = (string)$timeDom;

                $txts = $dom->find('.a-text');
                $fullBody = (count($txts)) ? $txts[0]->innertext : null;

                $textDom = HtmlDomParser::str_get_html($fullBody);

                $imgs = $textDom->find('p > img');
                $image_url = (count($imgs)) ? $imgs[0]->src : null;

                // Remove the first image which is the same with the main image
                $textDom->find('p')[0]->outertext = "";

                foreach ($textDom->find('.mesdok') as &$iframe) {
                    $iframe->outertext = "";
                }

                $content = trim((string)$textDom);
                $content .= "<p>Burimi: <a href='http://xing.al'>xing.al</a></p>";

                $html = new \Html2Text\Html2Text($content);

                $small_content = str_limit($html->getText(), 200);

                $link = "http://www.xing.al/lajm/{$fid}/xxx-alias";

                FetchedPost::create([
                    'fid' => $fid,
                    'title' => $title,
                    'small_content' => $small_content,
                    'content' => $content,
                    'image_url' => $image_url,
                    'category' => $category,
                    'category_mapped' => $categoryMapped,
                    'published_at' => $published_at,
                    'blog_url' => "http://xing.al"
                ]);

                $fid++;
            }

        } while ($continue);
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
