<?php

    namespace IdnoPlugins\Pigfire {
    
        use Idno\Core\Webservice;

        class Main extends \Idno\Common\Plugin {

            function registerPages() {
                \Idno\Core\site()->addPageHandler('/now', '\IdnoPlugins\Pigfire\Pages\Now');
                \Idno\Core\site()->addPageHandler('/history', '\IdnoPlugins\Pigfire\Pages\HistoryIndex');
                \Idno\Core\site()->addPageHandler('/history/([0-9a-fA-F\-]{36})/?', '\IdnoPlugins\Pigfire\Pages\History');
            }
            
        }

    }
