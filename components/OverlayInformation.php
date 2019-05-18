<?php

namespace Cleanse\Event\Components;

use Cms\Classes\ComponentBase;

use Cleanse\Event\Models\Broadcast;

class OverlayInformation extends ComponentBase
{
    private $information;

    public function componentDetails()
    {
        return [
            'name' => 'Information Box Overlay',
            'description' => 'Web source for info box overlay.'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'Broadcast ID',
                'description' => 'Broadcast identification.',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun()
    {
        $this->information = $this->getInfoData();

        $this->addCss('assets/css/overlay.css');
        $this->addJs('assets/js/overlay.js');

        $this->page['information'] = $this->information;
    }

    private function getInfoData()
    {
        $this->page['broadcast'] = $id = $this->property('id');
        $broadcast = Broadcast::find($id);

        if (!isset($broadcast)) {
            return [];
        }
        
        return $broadcast->information;
    }
}
