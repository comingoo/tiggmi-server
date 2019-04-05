<?php
namespace App\Services;

use App\Repositories\EventRepository;

class EventService
{
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     *Page  Siebar list
     *
     */

    public function getEventsList()
    {       
        $array = $this->eventRepository->getEventsList();
       return $array ;
    }
    public function  get_row_id_by_slug_key($tableName, $columnName, $slug){
        return $this->eventRepository->get_row_id_by_slug_key($tableName, $columnName, $slug) ;
    }

    public function get_events_details_by_id($id)
    {
        return $this->eventRepository->get_events_details_by_id($id) ;       
    }
    
    public function get_registered_for_event($id)
    {
        return $this->eventRepository->get_registered_for_event($id) ;       
    }
}

