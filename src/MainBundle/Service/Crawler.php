<?php

namespace MainBundle\Service;

use MainBundle\Entities\Page;

class Crawler
{
    protected $rootUrl;
    protected $crawlingUrl;
    protected $linksToCrawl = array();
    protected $pages = array();
    protected $content = array();
    protected $deep;
    protected $valdiator;

    public function __construct($url)
    {
        $this->setRootUrl($url);
        $this->valdiator = new valdiator();
    }

    public function run(){
        $page = new Page($this->rootUrl);
        $pageContent = $this->crawlSite($page);
        array_push($this->pages, $pageContent);
        $this->crawlSubPages($pageContent, 2);
        var_dump($this->pages);
    }

    public function crawlSubPages(Page $page, $deep = 2)
    {
        if ($deep < 1) {
            return;
        }

        if(empty($page->getLinks())) {
            return;
        }

        foreach ($page->getLinks() as $link){
            $subPage = $this->crawlSite(new Page($link['href']));
            array_push($this->pages, $subPage);
            $this->crawlSubPages($subPage, $deep - 1);
        }
    }

    public function crawlSite(Page $page)
    {
        $pageDoc = $page->getDocument();
        $page->setLinks($this->getATagElements($pageDoc));
        $page->setImages($this->getImageElements($pageDoc));
        $page->setBodyText($this->getBodyText($pageDoc));
        return $page;
    }

    function checkArrayForValue($array, $key, $val) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;
        return false;
    }

    // Set Element Functions

    public function getATagElements($pageDoc)
    {
        $elements = $pageDoc->getElementsByTagName('a');
        $content = null;
        foreach ($elements as $key => $tag) {
            if($tag->getAttribute('href') != "" && $tag->getAttribute('href') !== null && $this->valdiator->checkUrl($tag->getAttribute('href')) == true)
            {
                if(!empty($content))
                {
                    // Remove Duplicate entries
                    if($this->checkArrayForValue($content, 'href', $tag->getAttribute('href'))){
                        continue;
                    }
                }
                    $link = array(
                        'value' => null,
                        'href' => null,
                        'title' => null,
                    );
                    $link['value'] = $tag->nodeValue;
                    $link['href'] = $tag->getAttribute('href');
                    $link['title'] = $tag->getAttribute('title');
                $content[] = $link;
            }

        }
        return $content;
    }

    public function getImageElements($pageDoc)
    {
        $content = null;
        $elements = $pageDoc->getElementsByTagName('img');
        foreach ($elements as $tag)
        {
            if($tag->getAttribute('src') != "" || $tag->getAttribute('src') !== null)
            {
                $image =array(
                    'src' => null,
                    'title' => null,
                    'alt' => null,
                );

                $image['src'] = $tag->getAttribute('src');
                $image['title'] = $tag->getAttribute('title');
                $image['alt'] = $tag->getAttribute('alt');
                $content[] = $image;
            }

        }
        return $content;
    }

    public function getBodyText($pageDoc)
    {
        return htmlspecialchars($pageDoc->textContent);
    }

    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = $rootUrl;
    }


    // Getter Functions


    public function getContent()
    {
        return $this->content;
    }

}