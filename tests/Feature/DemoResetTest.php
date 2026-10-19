<?php

namespace Tests\Feature;

use App\Console\Commands\ResetDemoData;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DemoResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_recent_reset_with_the_current_schema_is_left_alone(): void
    {
        config(['demo.reset_hours' => 3]);
        Cache::forever('demo:last-reset', now()->timestamp);
        Cache::forever('demo:schema', ResetDemoData::schemaVersion());

        $this->get('/up')->assertOk();

        $this->assertSame(0, Subscription::count());
    }

    public function test_a_changed_schema_triggers_a_rebuild_even_before_the_timer(): void
    {
        config(['demo.reset_hours' => 3]);
        Cache::forever('demo:last-reset', now()->timestamp);
        Cache::forever('demo:schema', 'from an older deploy');

        $this->get('/up')->assertOk();

        $this->assertSame(ResetDemoData::schemaVersion(), Cache::get('demo:schema'));
        $this->assertGreaterThan(0, Subscription::count());
    }
}
