<?php

return [
    // Restore the sample data when it is older than this many hours. 0 turns it off (local default).
    'reset_hours' => (int) env('DEMO_RESET_HOURS', 0),
];
