<?php

namespace Seventyninepr\Wordpress\Boilerplate;

use Seventyninepr\Wordpress\Boilerplate\Core\Wordpress\DisableXmlRpc;
use Seventyninepr\Wordpress\Boilerplate\Core\Wordpress\RemoveEmojiScript;
use Seventyninepr\Wordpress\Boilerplate\Core\Wordpress\RemoveGenerator;
use Seventyninepr\Wordpress\Boilerplate\Core\Wordpress\RemovePingback;
use Seventyninepr\Wordpress\Boilerplate\Core\Wordpress\RemoveRsdLink;
use Seventyninepr\Wordpress\Boilerplate\Core\Wordpress\RemoveShortLink;
use Seventyninepr\Wordpress\Boilerplate\Core\Wordpress\RemoveWlwManifest;
use Seventyninepr\Wordpress\Boilerplate\Core\Timber\Defaults as TimberDefaults;

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
