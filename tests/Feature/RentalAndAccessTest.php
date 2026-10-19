<?php

namespace Tests\Feature;

use App\Models\Episode;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Season;
use App\Models\Show;
use App\Models\User;
use App\Services\AccessService;
use App\Services\RentalService;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RentalAndAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-03-10 10:00');
    }

    private function canWatch(User $user, Movie|Episode $content): bool
    {
        return app(AccessService::class)->check($user, $content)['allowed'];
    }

    public function test_a_rental_lasts_the_movies_rental_period(): void
    {
        $movie = Movie::factory()->premium(rentPrice: 15000)->create(['rent_days' => 3]);

        $rental = app(RentalService::class)->rent(User::factory()->create(), $movie);

        $this->assertSame(15000, $rental->amount);
        $this->assertSame('2026-03-13 10:00', $rental->expires_at->format('Y-m-d H:i'));
    }

    public function test_subscribers_cannot_rent_what_they_already_have(): void
    {
        $this->expectException(DomainException::class);
        app(RentalService::class)->rent(User::factory()->subscribedUntil('2026-04-01')->create(), Movie::factory()->premium(rentPrice: 15000)->create());
    }

    public function test_the_same_movie_cannot_be_rented_twice_at_once(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->premium(rentPrice: 15000)->create();
        app(RentalService::class)->rent($user, $movie);

        $this->expectException(DomainException::class);
        app(RentalService::class)->rent($user, $movie);
    }

    public function test_free_and_subscription_only_movies_cannot_be_rented(): void
    {
        foreach ([Movie::factory()->create(), Movie::factory()->premium(rentPrice: null)->create()] as $movie) {
            try {
                app(RentalService::class)->rent(User::factory()->create(), $movie);
                $this->fail("{$movie->title} was rented.");
            } catch (DomainException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_free_movies_play_for_everyone(): void
    {
        $this->assertTrue($this->canWatch(User::factory()->create(), Movie::factory()->create()));
    }

    public function test_premium_needs_an_active_subscription(): void
    {
        $movie = Movie::factory()->premium()->create();

        $this->assertFalse($this->canWatch(User::factory()->create(), $movie));
        $this->assertFalse($this->canWatch(User::factory()->subscribedUntil('2026-03-09')->create(), $movie));
        $this->assertTrue($this->canWatch(User::factory()->subscribedUntil('2026-03-10')->create(), $movie));
    }

    public function test_a_rental_unlocks_only_that_movie_and_only_until_it_expires(): void
    {
        $user = User::factory()->create();
        $rented = Movie::factory()->premium(rentPrice: 15000)->create();
        $other = Movie::factory()->premium(rentPrice: 15000)->create();
        app(RentalService::class)->rent($user, $rented);

        $this->assertTrue($this->canWatch($user, $rented));
        $this->assertFalse($this->canWatch($user, $other));

        Carbon::setTestNow('2026-03-12 10:01');
        $this->assertFalse($this->canWatch($user, $rented));
    }

    public function test_an_inactive_account_or_title_cannot_be_watched(): void
    {
        $this->assertFalse($this->canWatch(User::factory()->create(['is_active' => false]), Movie::factory()->create()));
        $this->assertFalse($this->canWatch(User::factory()->create(), Movie::factory()->create(['is_active' => false])));
    }

    public function test_an_episode_is_premium_on_its_own_or_through_its_series(): void
    {
        $season = Season::create(['name' => 'Season 1']);
        $free = Show::create(['title' => 'Gratis', 'language_id' => Language::factory()->create()->id, 'release_year' => 2024]);
        $paid = Show::create(['title' => 'Berbayar', 'language_id' => $free->language_id, 'release_year' => 2024, 'is_premium' => true]);
        $open = $free->episodes()->create(['season_id' => $season->id, 'episode_number' => 1, 'title' => 'E1', 'duration_minutes' => 30]);
        $locked = $free->episodes()->create(['season_id' => $season->id, 'episode_number' => 2, 'title' => 'E2', 'duration_minutes' => 30, 'is_premium' => true]);
        $series = $paid->episodes()->create(['season_id' => $season->id, 'episode_number' => 1, 'title' => 'E1', 'duration_minutes' => 30]);
        $viewer = User::factory()->create();

        $this->assertTrue($this->canWatch($viewer, $open));
        $this->assertFalse($this->canWatch($viewer, $locked));
        $this->assertFalse($this->canWatch($viewer, $series));
    }

    public function test_the_user_page_answers_whether_they_can_watch_a_title(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->premium(rentPrice: 15000)->create();

        $this->get(route('users.show', [$user, 'content' => "movie:{$movie->id}"]))
            ->assertOk()->assertSee('Rp 15.000');
    }
}
