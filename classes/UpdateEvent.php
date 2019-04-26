<?php

namespace Cleanse\Event\Classes;

use Cleanse\Event\Classes\Helpers\FactoryHelper;

class UpdateEvent
{
    private $source;

    public function __construct()
    {
        $this->source = [
            'namespace' => 'Cleanse\\Event\\Classes\\Formats\\',
            'target'    => 'Updater'
        ];
    }

    public function advanceMatch($data)
    {
        return ((new FactoryHelper)->getInstance($this->source, $data->event->type))->advance($data);
    }

    /**
     * @param $data
     * @return mixed
     *
     * todo last
     */
    public function undoMatch($data)
    {
        return ((new FactoryHelper)->getInstance($this->source, $data->event->type))->undo($data);
    }
}
