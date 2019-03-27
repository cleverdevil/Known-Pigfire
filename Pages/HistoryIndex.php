<?php

    namespace IdnoPlugins\Pigfire\Pages {

        use Idno\Core\Webmention;
        use Idno\Entities\Notification;
        use Idno\Entities\User;

        class HistoryIndex extends \Idno\Common\Page
        {

            function getContent()
            {
                $description = $description . "🐷🔥 :: History";
                $title = $description;

                $inhale_endpoint = \Idno\Core\Idno::site()->config()->inhale;

                // fetch the list of cooks
                $cooks_json = file_get_contents(
                   $inhale_endpoint . '/cooks.json'
                );
                $cooks = json_decode($cooks_json);

                $t = \Idno\Core\Idno::site()->template();
                $t->__(array(
                    'title'       => $title,
                    'description' => $description,
                    'content'     => array('all'), 
                    'body'        => $t->__(array(
                        'cooks'   => $cooks 
                    ))->draw('pages/historyindex'),
                ))->drawPage();
            }

        }

    }
