<?php

    namespace IdnoPlugins\Pigfire\Pages {

        use Idno\Core\Webmention;
        use Idno\Entities\Notification;
        use Idno\Entities\User;

        class Now extends \Idno\Common\Page
        {

            function getContent()
            {
                /*
                $status_file = fopen("current.json", "r");
                $raw_json = fgets($status_file);
                $status = json_decode($raw_json, true);
                */

                // Check for an empty site
                if (!\Idno\Entities\User::get()) {
                    $this->forward(\Idno\Core\Idno::site()->config()->getURL() . 'begin/');
                }

                $description = $description . '🐷🔥 :: Now'; 
                $title = $description;

                $t = \Idno\Core\Idno::site()->template();
                $t->__(array(
                    'title'       => $title,
                    'description' => $description,
                    'content'     => array('all'),
                    'body'        => $t->__(array(
                        'status'  => array() 
                    ))->draw('pages/now'),
                ))->drawPage();
            }

        }

    }
