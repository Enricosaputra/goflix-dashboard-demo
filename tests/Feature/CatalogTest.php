<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Package;
use App\Models\Person;
use App\Models\Season;
use App\Models\Show;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_movie_is_saved_with_its_genres_and_cast(): void
    {
        $language = Language::factory()->create();
        $genres = Genre::factory()->count(2)->create();
        $director = Person::create(['name' => 'Rizal', 'role' => 'director']);

        $this->post(route('movies.store'), [
            'title' => 'Senja', 'language_id' => $language->id, 'genres' => $genres->pluck('id')->all(), 'people' => [$director->id],
            'release_year' => 2024, 'duration_minutes' => 100, 'rent_days' => 2, 'max_quality' => '1080p', 'is_premium' => 1, 'rent_price' => 15000,
        ])->assertRedirect(route('movies.index'));

        $movie = Movie::where('title', 'Senja')->firstOrFail();
        $this->assertEqualsCanonicalizing($genres->pluck('id')->all(), $movie->genres()->pluck('genres.id')->all());
        $this->assertSame([$director->id], $movie->people()->pluck('people.id')->all());
        $this->assertTrue($movie->isRentable());
    }

    public function test_a_free_movie_cannot_have_a_rent_price(): void
    {
        $this->post(route('movies.store'), [
            'title' => 'Gratis', 'language_id' => Language::factory()->create()->id, 'genres' => [Genre::factory()->create()->id],
            'release_year' => 2024, 'duration_minutes' => 100, 'rent_days' => 2, 'max_quality' => '720p', 'rent_price' => 15000,
        ])->assertSessionHasErrors('rent_price');
    }

    public function test_a_series_is_saved_with_its_episodes_and_keeps_them_on_edit(): void
    {
        $language = Language::factory()->create();
        $genre = Genre::factory()->create();
        $s1 = Season::create(['name' => 'Season 1']);
        $payload = fn (array $episodes) => [
            'title' => 'Kos-Kosan', 'language_id' => $language->id, 'genres' => [$genre->id], 'release_year' => 2023, 'episodes' => $episodes,
        ];

        $this->post(route('shows.store'), $payload([
            ['season_id' => $s1->id, 'episode_number' => 1, 'title' => 'Pindahan', 'duration_minutes' => 30],
            ['season_id' => $s1->id, 'episode_number' => 2, 'title' => 'Listrik Mati', 'duration_minutes' => 28, 'is_premium' => 1],
            ['season_id' => $s1->id, 'episode_number' => 3, 'title' => '', 'duration_minutes' => ''],
        ]))->assertRedirect(route('shows.index'));
        $show = Show::where('title', 'Kos-Kosan')->firstOrFail();
        $first = $show->episodes()->first();

        $this->put(route('shows.update', $show), $payload([
            ['season_id' => $s1->id, 'episode_number' => 1, 'title' => 'Hari Pindahan', 'duration_minutes' => 31],
        ]))->assertRedirect(route('shows.index'));

        $this->assertSame(['Hari Pindahan'], $show->episodes()->pluck('title')->all());
        $this->assertSame($first->id, $show->episodes()->first()->id); // same row, so viewing history stays attached
    }

    public function test_the_same_episode_number_twice_in_a_season_is_refused(): void
    {
        $season = Season::create(['name' => 'Season 1']);

        $this->post(route('shows.store'), [
            'title' => 'Dobel', 'language_id' => Language::factory()->create()->id, 'genres' => [Genre::factory()->create()->id], 'release_year' => 2023,
            'episodes' => [
                ['season_id' => $season->id, 'episode_number' => 1, 'title' => 'A', 'duration_minutes' => 30],
                ['season_id' => $season->id, 'episode_number' => 1, 'title' => 'B', 'duration_minutes' => 30],
            ],
        ])->assertSessionHasErrors('episodes');
    }

    public function test_things_in_use_cannot_be_deleted(): void
    {
        $movie = Movie::factory()->create();
        $genre = Genre::factory()->create();
        $movie->genres()->attach($genre);
        $package = Package::factory()->create();
        $user = User::factory()->create();
        app(SubscriptionService::class)->subscribe($user, $package);

        $this->delete(route('genres.destroy', $genre))->assertSessionHas('error');
        $this->delete(route('languages.destroy', $movie->language))->assertSessionHas('error');
        $this->delete(route('packages.destroy', $package))->assertSessionHas('error');
        $this->delete(route('users.destroy', $user))->assertSessionHas('error');

        $this->assertModelExists($genre);
        $this->assertModelExists($package);
        $this->assertModelExists($user);
    }

    public function test_payments_cannot_be_edited_or_deleted(): void
    {
        $this->assertFalse(app('router')->has('subscriptions.update'));
        $this->assertFalse(app('router')->has('subscriptions.destroy'));
        $this->assertFalse(app('router')->has('rentals.destroy'));
    }

    public function test_a_user_email_with_a_line_break_is_refused(): void
    {
        $this->post(route('users.store'), ['name' => 'X', 'email' => "x@example.com\r\nBcc: y@example.com", 'login_type' => 'email'])
            ->assertSessionHasErrors('email');
    }

    public function test_every_page_loads_with_the_demo_data(): void
    {
        $this->seed();
        $user = User::first();

        foreach (['dashboard', 'movies.index', 'movies.create', 'shows.index', 'shows.create', 'genres.index', 'languages.index', 'people.index', 'seasons.index',
            'packages.index', 'packages.create', 'coupons.index', 'users.index', 'subscriptions.index', 'subscriptions.create', 'rentals.index', 'rentals.create'] as $route) {
            $this->get(route($route))->assertOk();
        }
        $this->get(route('users.show', $user))->assertOk();
        $this->get(route('shows.edit', Show::first()))->assertOk();
        $this->get(route('movies.edit', Movie::first()))->assertOk();
    }
}
