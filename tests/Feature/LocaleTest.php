<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_app_speaks_indonesian_by_default(): void
    {
        $this->get(route('movies.index'))->assertSee('Film')->assertSee('Langganan');
    }

    public function test_visitors_can_switch_to_english_and_back(): void
    {
        $this->from(route('movies.index'))->get(route('locale', 'en'))->assertRedirect(route('movies.index'));
        $this->get(route('movies.index'))->assertSee('Movies')->assertSee('Subscriptions');

        $this->get(route('locale', 'id'));
        $this->get(route('movies.index'))->assertSee('Langganan');
    }

    public function test_unknown_languages_are_rejected(): void
    {
        $this->get('/locale/fr')->assertNotFound();
    }

    public function test_validation_messages_follow_the_language(): void
    {
        $this->post(route('coupons.store'), ['code' => '', 'amount_type' => 'percent', 'amount' => 10, 'starts_on' => '2026-01-01', 'ends_on' => '2026-01-31'])
            ->assertSessionHasErrors(['code' => 'Kode wajib diisi.']);
    }
}
