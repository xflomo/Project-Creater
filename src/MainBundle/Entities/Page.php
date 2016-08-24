<?php

namespace MainBundle\Entities;

use MainBundle\Service\valdiator;

class Page
{
    public $rootUrl;
    public $document;
    public $bodyText;
    public $links = array();
    public $images = array();
    public $valdiator;

    public function __construct($url)
    {
        $this->valdiator = new valdiator();
        if($this->valdiator->checkUrl($url)){
            $this->setRootUrl($url);
            $this->setDocument();
        }
    }

    public function setDocument(){
        $this->document = new \DOMDocument();
        $this->document->loadHTMLFile($this->rootUrl);
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return mixed
     */
    public function getBodyText()
    {
        return $this->bodyText;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return mixed
     */
    public function getRootUrl()
    {
        return $this->rootUrl;
    }
    /**
     * @param mixed $rootUrl
     */
    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = $rootUrl;
    }

    /**
     * @param mixed $bodyText
     */
    public function setBodyText($bodyText)
    {
        $this->bodyText = $bodyText;
    }

    /**
     * @param array $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @param array $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }

}