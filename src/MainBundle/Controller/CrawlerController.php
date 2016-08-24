<?php

namespace MainBundle\Controller;

use MainBundle\Entities\Page;
use MainBundle\Service\Crawler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CrawlerController extends Controller
{
    /**
     * @Route("/crawling", name="crawling")
     */
    public function crawlingAction(Request $request)
    {
        // Disable Warnings
        libxml_use_internal_errors(true);
        set_time_limit(200);
        $crawler = new Crawler("http://fb-dev.de/");
        $crawler->run();

        return $this->render('MainBundle:default:index.html.twig');
    }
}
