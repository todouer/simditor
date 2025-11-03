<?php

namespace Tudouer\Simditor;

use Casbin\Admin\Extension;

class SimditorExtension extends Extension
{
    public $name = 'simditor';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';
}
