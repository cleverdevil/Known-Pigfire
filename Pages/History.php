<?php

    namespace IdnoPlugins\Pigfire\Pages {

        use Idno\Core\Webmention;
        use Idno\Entities\Notification;
        use Idno\Entities\User;

        class History extends \Idno\Common\Page
        {

            function getContent()
            {
                $description = $description . "🐷🔥 :: History";
                $title = $description;

                $t = \Idno\Core\Idno::site()->template();
                $t->__(array(
                    'title'       => $title,
                    'description' => $description,
                    'content'     => array('all'), 
                    'body'        => $t->__(array(
                        'cook_id' => $this->arguments[0], 
                    ))->draw('pages/history'),

                ))->drawPage();
            }

        }

    }
