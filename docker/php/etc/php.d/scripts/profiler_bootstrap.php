<?php

/**
 * This bootstrap file is included at boot-up for PHP, and is used to gracefully discover the Profiler Bootstrap.
 * If the profiler bootstrap is unavailable, nothing happens.
 *
 * This is _marginally_ better than the PHP Error you get if you try to include a missing file directory.
 */

// Target
$bootstrap = '/var/www/survey-backend/vendor/elmo/testing-framework/src/bootstrap.php';
@include_once($bootstrap);
