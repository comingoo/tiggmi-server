<?php
namespace App\Services;

use App\Repositories\PageRepository;

class PageService
{
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     *Page  Siebar list
     *
     */

    public function getSidebar()
    {
       
       // $array = $this->pageRepository->getSidebar();


      // return $array ;
    }

}

