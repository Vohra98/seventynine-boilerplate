<?php

namespace seventynine\Wordpress\Boilerplate;

use seventynine\Wordpress\Boilerplate\Core\Wordpress\DisableXmlRpc;
use seventynine\Wordpress\Boilerplate\Core\Wordpress\RemoveEmojiScript;
use seventynine\Wordpress\Boilerplate\Core\Wordpress\RemoveGenerator;
use seventynine\Wordpress\Boilerplate\Core\Wordpress\RemovePingback;
use seventynine\Wordpress\Boilerplate\Core\Wordpress\RemoveRsdLink;
use seventynine\Wordpress\Boilerplate\Core\Wordpress\RemoveShortLink;
use seventynine\Wordpress\Boilerplate\Core\Wordpress\RemoveWlwManifest;
use seventynine\Wordpress\Boilerplate\Core\Timber\Defaults as TimberDefaults;

class Boilerplate
{
    /**
     * Start the boilerplate bootstrap process.
     */
    public static function bootstrap()
    {
        DisableXmlRpc::run();
        RemoveEmojiScript::run();
        RemoveGenerator::run();
        RemovePingback::run();
        RemoveRsdLink::run();
        RemoveShortLink::run();
        RemoveWlwManifest::run();
        TimberDefaults::run();
    }
}
