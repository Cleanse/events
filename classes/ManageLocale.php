<?php

namespace Cleanse\Event\Classes;

class ManageLocale
{
    public function getLocale($language)
    {
        $lang = './plugins/cleanse/event/classes/locale/' . $language . '/lang.php';
        return $this->getLanguage($lang);
    }

    private function getLanguage($language)
    {
        if (is_file($language)) {
            return include $language;
        }

        return false;
    }
}
