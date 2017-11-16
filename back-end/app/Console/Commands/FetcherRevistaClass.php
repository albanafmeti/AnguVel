<?php

namespace App\Console\Commands;

use App\FetchedPost;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class FetcherRevistaClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetcher:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Content fetcher for revistaclass.al blog';

    private $mappedCategs = [];

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
        $this->mapCategories();

        ini_set('max_execution_time', 299);
        $lastFetched = FetchedPost::where('blog_url', 'http://www.revistaclass.al')->orderBy('id', 'desc')->first();

        if ($lastFetched) {
            $fid = (int)$lastFetched->fid;
            $fid++;
        } else {
            $fid = 22580;
        }

        $data = [];

        foreach ($this->categories() as $category) {

            foreach ($category['sub'] as $subcateg) {

                try {

                    $fecthedUrl = "http://www.revistaclass.al/sektlisteajax.php?i=1&id={$category['id']}&idd={$subcateg['id']}";

                    $dom = HtmlDomParser::file_get_html($fecthedUrl);
                    $elems = $dom->find("ul.tre-artikuj-list li");
                    foreach ($elems as $elem) {

                        $links = $elem->find('a');
                        $link = (count($links)) ? $links[0]->href : null;


                        $s = str_replace('http://www.revistaclass.al/', '', $link);
                        $linkParts = explode("/", ltrim($s, '/'));
                        $id = (isset($linkParts[3]) && is_numeric($linkParts[3])) ? (int)$linkParts[3] : null;

                        if ($id <= $fid) {
                            continue;
                        }

                        $categs = $elem->find('p.category');
                        $categoryName = (count($categs)) ? trim($categs[0]->innertext) : null;

                        $categoryMapped = $this->getMappedCategory($categoryName);

                        $data[] = (object)[
                            "id" => $id,
                            "category" => $categoryName,
                            "category_mapped" => $categoryMapped,
                            "link" => $link,
                        ];
                    }

                    foreach ($data as &$element) {

                        $dom = HtmlDomParser::file_get_html($element->link);

                        $tits = $dom->find('.article-header h1');
                        $title = (count($tits)) ? $tits[0]->innertext : null;

                        $element->title = $title;

                        $times = $dom->find('.content > div');
                        $time = (count($times)) ? $times[0]->innertext : null;

                        $element->published_at = $time;

                        $imgs = $dom->find('.artikulli > .article-body .tekst p > img');
                        $image_url = (count($imgs)) ? $imgs[0]->src : null;

                        $element->image_url = $image_url;

                        $txts = $dom->find('.artikulli > .article-body .tekst');
                        $fullBody = (count($txts)) ? $txts[0]->innertext : null;

                        $textDom = HtmlDomParser::str_get_html($fullBody);

                        // Remove the first image which is the same with the main image
                        $textDom->find('p')[0]->outertext = "";

                        foreach ($textDom->find('div.rekmes') as &$iframe) {
                            $iframe->outertext = "";
                        }

                        foreach ($textDom->find('.addthis_sharing_toolbox') as &$iframe) {
                            $iframe->outertext = "";
                        }

                        $content = trim((string)$textDom);
                        $content .= "<p>Burimi: <a href='http://revistaclass.al'>revistaclass.al</a></p>";
                        $element->content = $content;

                        $html = new \Html2Text\Html2Text($content);
                        $small_content = str_limit($html->getText(), 200);
                        $element->small_content = $small_content;
                    }
                } catch (\Exception $ex) {
                    continue;
                }

                $this->info("Finished fetching category: " . $subcateg['name']);
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
                'category' => $article->category,
                'category_mapped' => $article->category_mapped,
                'published_at' => $article->published_at,
                'blog_url' => "http://revistaclass.al"
            ]);
        }
    }

    private function categories()
    {
        return [
            [
                "id" => 130,
                "name" => "Personazh",
                "sub" => [
                    [
                        "id" => 133,
                        "name" => "Të famshëm",
                        "mapped" => "Showbiz"
                    ],
                    [
                        "id" => 134,
                        "name" => "Nga jeta e çdo dite",
                        "mapped" => "Showbiz"
                    ],
                    [
                        "id" => 176,
                        "name" => "Talente",
                        "mapped" => "Showbiz"
                    ],
                    [
                        "id" => 169,
                        "name" => "Love Story",
                        "mapped" => "Showbiz"
                    ]
                ]
            ],
            [
                "id" => 197,
                "name" => "Showbizz",
                "sub" => [
                    [
                        "id" => 135,
                        "name" => "TV&Show",
                        "mapped" => "Showbiz"
                    ],
                    [
                        "id" => 201,
                        "name" => "Vip",
                        "mapped" => "Showbiz"
                    ],
                    [
                        "id" => 207,
                        "name" => "Your Face Sounds Familiar",
                        "mapped" => "Showbiz"
                    ],
                    [
                        "id" => 100,
                        "name" => "Mami, babi dhe bebi VIP",
                        "mapped" => "Showbiz"
                    ]
                ]
            ],
            [
                "id" => 216,
                "name" => "Gossip",
                "sub" => [
                    [
                        "id" => 175,
                        "name" => "Gossip",
                        "mapped" => "Showbiz"
                    ]
                ]
            ],
            [
                "id" => 124,
                "name" => "Class Life",
                "sub" => [
                    [
                        "id" => 10,
                        "name" => "Gastronomi",
                        "mapped" => "Lifestyle"
                    ],
                    [
                        "id" => 9,
                        "name" => "Arredimi dhe shtëpia",
                        "mapped" => "Lifestyle"
                    ],
                    [
                        "id" => 12,
                        "name" => "Udhëtime",
                        "mapped" => "Lifestyle"
                    ],
                    [
                        "id" => 167,
                        "name" => "Shkencë&Teknologji",
                        "mapped" => "Lifestyle"
                    ],
                    [
                        "id" => 96,
                        "name" => "Stil jete",
                        "mapped" => "Lifestyle"
                    ],
                    [
                        "id" => 172,
                        "name" => "Karrierë",
                        "mapped" => "Lifestyle"
                    ],

                ]
            ],
            [
                "id" => 198,
                "name" => "Aktualitet",
                "sub" => [
                    [
                        "id" => 171,
                        "name" => "Po ndodh tani",
                        "mapped" => "Aktualitet"
                    ]
                ]
            ],
        ];
    }

    private function getMappedCategory($category)
    {
        if (isset($this->mappedCategs[$category])) {
            return $this->mappedCategs[$category];
        }
    }

    private function mapCategories()
    {
        foreach ($this->categories() as $category) {
            foreach ($category['sub'] as $subcateg) {
                $this->mappedCategs[$subcateg['name']] = $subcateg['mapped'];
            }
        }
    }
}
