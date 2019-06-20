<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('home/index.html.twig', [

        ]);
    }

    /**
     * @Route("/scraper", name="scraper")
     *
     * @param Request $request
     * @return Response
     */
    public function scraper(Request $request)
    {
        $domains = preg_split('/\r\n|\r|\n/', $request->request->get('content'));

        $result = [];

        if ($request->get('code') === 'upwork') {
            foreach ($domains as $domain) {
                if ($domain) {
                    $result[$domain] = [
                        'name' => $domain,
                        'pages'  => []
                    ];
                    $pages = ['/'];
                    $crawledPages = [];

                    while (true) {
                        if (count(array_intersect($pages, $crawledPages)) === count($pages)) {
                            break;
                        }

                        foreach ($pages as $page) {
                            if (!in_array($page, $crawledPages)) {
                                $data = $this->crawlLink($domain . $page);
                                $crawledPages[] = $page;
                                $pages = array_unique(array_merge($pages, $data['internal']));
                                $result[$domain]['pages'][] = [
                                    'name'           => $page,
                                    'external_links' => $data['external']
                                ];
                                break;
                            }
                        }
                    }
                }
            }

            $success = true;
        } else {
            $success = false;
        }


        return $this->render('home/scraper.html.twig', [
            'content' => $request->get('content'),
            'code'    => $request->get('code'),
            'data'    => $result,
            'success' => $success,
        ]);
    }

    private function crawlLink(string $link)
    {
        $result = [
            'internal' => [],
            'external' => [],
        ];
        $crawler = new Crawler(file_get_contents($link));
        $links = $crawler->filter('a');

        foreach ($links as $link) {
            $href = $link->getAttribute('href');

            if (substr($href, 0, 1) === '/') {
                $result['internal'][] = $href;
            } elseif (substr($href, 0, 4) === 'http') {
                $result['external'][] = $href;
            }
        }

        return $result;
    }
}
