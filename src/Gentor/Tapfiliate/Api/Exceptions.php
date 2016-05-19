<?php

namespace Gentor\Tapfiliate;

use Exception;

class TapfiliateError extends Exception
{
}

class TapfiliateRateLimit extends TapfiliateError
{
}
