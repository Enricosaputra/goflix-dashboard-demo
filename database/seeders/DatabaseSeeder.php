<?php

namespace Database\Seeders;

use App\Enums\AmountType;
use App\Enums\LoginType;
use App\Enums\PersonRole;
use App\Models\Coupon;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Package;
use App\Models\Person;
use App\Models\Season;
use App\Models\Show;
use App\Models\User;
use App\Services\RentalService;
use App\Services\SubscriptionService;
use DomainException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

// Made-up catalog, users and payments. Titles and names are invented; nothing comes from the real system.
class DatabaseSeeder extends Seeder
{
    public function run(SubscriptionService $subscriptions, RentalService $rentals): void
    {
        mt_srand(7); // same demo data on every run

        $seasons = collect(range(1, 5))->map(fn ($n) => Season::create(['name' => "Season $n"]));
        $genres = collect(['Action', 'Adventure', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Horror', 'Romance', 'Thriller', 'Sport'])
            ->mapWithKeys(fn ($name) => [$name => Genre::create(['name' => $name])]);
        Genre::create(['name' => 'Breaking News', 'is_active' => false]);
        $languages = collect(['Indonesia', 'English'])->mapWithKeys(fn ($name) => [$name => Language::create(['name' => $name])]);
        $people = $this->people();

        $movies = $this->movies($genres, $languages, $people);
        $shows = $this->shows($genres, $languages, $seasons);
        $packages = $this->packages();
        $coupons = $this->coupons();
        $users = $this->users();

        $this->payments($users, $packages, $coupons, $movies, $subscriptions, $rentals);
        $this->viewing($users, $movies, $shows);
    }

    private function people(): Collection
    {
        $names = [
            PersonRole::Director->value => ['Rizal Mahendra', 'Sari Wulandari', 'Bimo Prasetyo', 'Lintang Ayu', 'Hendra Kusuma', 'Maya Larasati'],
            PersonRole::Actor->value => ['Dimas Saputra', 'Raka Pratama', 'Yoga Firmansyah', 'Arif Nugraha', 'Bayu Aditya', 'Fajar Ramadhan', 'Galih Permana', 'Kevin Wijaya', 'Andre Susanto', 'Iqbal Hakim', 'Teguh Santoso', 'Rendy Gunawan'],
            PersonRole::Actress->value => ['Nadia Putri', 'Citra Anjani', 'Ayu Lestari', 'Dewi Kartika', 'Rani Oktaviani', 'Tasya Amelia', 'Indah Permatasari', 'Laras Sekar', 'Putri Maharani', 'Wulan Sari', 'Kirana Dewi', 'Salsa Febrianti'],
        ];

        return collect($names)->flatMap(fn ($list, $role) => collect($list)->map(fn ($name) => Person::create(['name' => $name, 'role' => $role])));
    }

    private function movies(Collection $genres, Collection $languages, Collection $people): Collection
    {
        // title, genres, language, year, minutes, age rating, imdb, premium, rent price (null = not for rent)
        $catalog = [
            ['Senja di Pelabuhan', ['Drama', 'Romance'], 'Indonesia', 2023, 112, '13+', 7.4, true, 15000],
            ['Rumah di Ujung Gang', ['Horror', 'Thriller'], 'Indonesia', 2024, 98, '17+', 6.8, true, 15000],
            ['Kopi Pahit', ['Comedy', 'Drama'], 'Indonesia', 2022, 104, '13+', 7.1, false, null],
            ['Langit Merah Jakarta', ['Action', 'Crime'], 'Indonesia', 2024, 121, '17+', 7.0, true, 20000],
            ['Pulang Kampung', ['Family', 'Comedy'], 'Indonesia', 2021, 95, 'SU', 7.6, false, null],
            ['Tikungan Terakhir', ['Action', 'Sport'], 'Indonesia', 2023, 109, '13+', 6.5, true, 15000],
            ['Bayang di Cermin', ['Horror'], 'Indonesia', 2022, 92, '17+', 6.1, false, null],
            ['Surat untuk Ibu', ['Drama', 'Family'], 'Indonesia', 2020, 118, 'SU', 8.1, true, 10000],
            ['Kilat Biru', ['Action', 'Fantasy'], 'Indonesia', 2025, 127, '13+', 6.9, true, 25000],
            ['Penjaga Mercusuar', ['Thriller', 'Drama'], 'Indonesia', 2023, 101, '17+', 7.3, true, null],
            ['Seratus Hari Bersamamu', ['Romance'], 'Indonesia', 2024, 106, '13+', 6.7, true, 15000],
            ['Detektif Kampus', ['Comedy', 'Crime'], 'Indonesia', 2022, 99, '13+', 6.4, false, null],
            ['Anak Gunung', ['Adventure', 'Family'], 'Indonesia', 2021, 103, 'SU', 7.2, false, null],
            ['Laut Tak Bertepi', ['Documentary'], 'Indonesia', 2023, 88, 'SU', 8.3, false, null],
            ['Garis Finis', ['Sport', 'Drama'], 'Indonesia', 2024, 114, '13+', 7.0, true, 15000],
            ['Kota Tanpa Tidur', ['Crime', 'Thriller'], 'Indonesia', 2025, 124, '21+', 7.5, true, 25000],
            ['Rahasia Nenek', ['Comedy', 'Family'], 'Indonesia', 2023, 93, 'SU', 6.9, false, null],
            ['Malam Satu Suro', ['Horror'], 'Indonesia', 2024, 97, '17+', 6.6, true, 15000],
            ['The Last Ferry', ['Thriller', 'Adventure'], 'English', 2022, 110, '17+', 6.8, true, 20000],
            ['Northern Lights', ['Romance', 'Drama'], 'English', 2023, 102, '13+', 7.1, true, null],
            ['Paper Kingdom', ['Fantasy', 'Family'], 'English', 2021, 96, 'SU', 6.5, false, null],
            ['Silent Harbour', ['Crime', 'Drama'], 'English', 2024, 119, '17+', 7.4, true, 20000],
            ['Wild Coast', ['Documentary', 'Adventure'], 'English', 2022, 85, 'SU', 7.9, false, null],
            ['Midnight Run Club', ['Comedy', 'Sport'], 'English', 2025, 101, '13+', 6.3, true, 15000],
        ];
        $directors = $people->filter(fn ($p) => $p->role === PersonRole::Director)->values();
        $cast = $people->reject(fn ($p) => $p->role === PersonRole::Director)->values();

        return collect($catalog)->map(function ($m) use ($genres, $languages, $directors, $cast) {
            $movie = Movie::create([
                'title' => $m[0], 'language_id' => $languages[$m[2]]->id, 'release_year' => $m[3], 'duration_minutes' => $m[4],
                'maturity_rating' => $m[5], 'imdb_rating' => $m[6], 'is_premium' => $m[7], 'rent_price' => $m[8],
                'rent_days' => $m[8] >= 20000 ? 3 : 2, 'is_downloadable' => ! $m[7], 'max_quality' => $m[7] ? '1080p' : '720p',
                'description' => "{$m[0]} adalah film {$m[3]} bergenre ".strtolower(implode(' dan ', $m[1])).'. Sinopsis ini dibuat untuk data demo.',
            ]);
            $movie->genres()->sync(collect($m[1])->map(fn ($g) => $genres[$g]->id));
            $movie->people()->sync(collect([$directors[mt_rand(0, $directors->count() - 1)]])
                ->merge(collect(range(1, 3))->map(fn () => $cast[mt_rand(0, $cast->count() - 1)]))->pluck('id')->unique());

            return $movie;
        });
    }

    private function shows(Collection $genres, Collection $languages, Collection $seasons): Collection
    {
        $series = [
            ['Kos-Kosan Bu Ratna', ['Comedy', 'Family'], false, [6, 6], 'Komedi sehari-hari penghuni kos di Yogyakarta.'],
            ['Unit Kejahatan Siber', ['Crime', 'Thriller'], true, [8], 'Tim kecil polisi mengejar penipu daring.'],
            ['Jejak Rasa Nusantara', ['Documentary'], false, [6], 'Perjalanan mencari masakan daerah.'],
        ];

        return collect($series)->map(function ($s) use ($genres, $languages, $seasons) {
            $show = Show::create([
                'title' => $s[0], 'description' => $s[4], 'language_id' => $languages['Indonesia']->id, 'release_year' => 2023,
                'maturity_rating' => $s[2] ? '17+' : 'SU', 'imdb_rating' => 7.0 + mt_rand(0, 12) / 10, 'is_premium' => $s[2],
            ]);
            $show->genres()->sync(collect($s[1])->map(fn ($g) => $genres[$g]->id));
            foreach ($s[3] as $seasonIndex => $episodes) {
                foreach (range(1, $episodes) as $n) {
                    // From season 2 on, the newest episodes are premium even in a free series.
                    $show->episodes()->create([
                        'season_id' => $seasons[$seasonIndex]->id, 'episode_number' => $n, 'title' => "Episode $n",
                        'duration_minutes' => mt_rand(22, 48), 'is_premium' => $seasonIndex > 0 && $n > 3,
                    ]);
                }
            }

            return $show->load('episodes');
        });
    }

    private function packages(): Collection
    {
        $plans = [
            ['Mobile', 15000, 1, 1, '480p', false, [['Semua konten', 'Ya'], ['Tonton di TV atau laptop', 'Tidak']]],
            ['Premium', 35000, 1, 2, '1080p', true, [['Semua konten', 'Ya'], ['Tanpa iklan', 'Ya'], ['Unduh untuk ditonton offline', 'Ya']]],
            ['Premium 12 Bulan', 350000, 12, 2, '1080p', true, [['Semua konten', 'Ya'], ['Tanpa iklan', 'Ya'], ['Hemat dibanding bulanan', '2 bulan gratis']]],
        ];
        $packages = collect($plans)->map(function ($p) {
            $package = Package::create([
                'name' => $p[0], 'price' => $p[1], 'duration_months' => $p[2], 'max_devices' => $p[3], 'max_quality' => $p[4],
                'watch_on_tv' => $p[5], 'ads_free' => $p[0] !== 'Mobile',
            ]);
            $package->setFeatures(collect($p[6])->map(fn ($f) => ['name' => $f[0], 'value' => $f[1]])->all());

            return $package;
        });
        Package::create(['name' => 'Promo Lama', 'price' => 10000, 'duration_months' => 1, 'max_devices' => 1, 'max_quality' => '480p', 'is_active' => false]);

        return $packages;
    }

    private function coupons(): Collection
    {
        $year = now()->subMonths(11)->startOfMonth();

        return collect([
            Coupon::create(['code' => 'HEMAT10', 'amount_type' => AmountType::Percent, 'amount' => 10, 'starts_on' => $year, 'ends_on' => now()->addMonths(2), 'single_use' => true]),
            Coupon::create(['code' => 'NONTON5K', 'amount_type' => AmountType::Fixed, 'amount' => 5000, 'starts_on' => $year, 'ends_on' => now()->addMonth()]),
            Coupon::create(['code' => 'LEBARAN25', 'amount_type' => AmountType::Percent, 'amount' => 25, 'starts_on' => $year->copy()->subYear(), 'ends_on' => $year->copy()->subMonths(10)]),
        ]);
    }

    private function users(): Collection
    {
        $first = ['Adi', 'Bunga', 'Cahya', 'Dina', 'Eko', 'Fitri', 'Gita', 'Hadi', 'Intan', 'Joko', 'Kartika', 'Lukman', 'Mira', 'Nanda', 'Oki', 'Putu', 'Rina', 'Sinta', 'Tono', 'Umi', 'Vina', 'Wahyu', 'Yuni', 'Zaki'];
        $last = ['Santoso', 'Wijaya', 'Pratama', 'Lestari', 'Hidayat', 'Saputra', 'Kurniawan', 'Rahayu', 'Siregar', 'Nasution', 'Purba', 'Halim'];
        $types = [LoginType::Google, LoginType::Google, LoginType::Google, LoginType::Google, LoginType::Email, LoginType::Email, LoginType::Otp, LoginType::Otp, LoginType::Facebook, LoginType::Apple];

        $users = collect();
        foreach (range(11, 0) as $back) {
            $month = now()->startOfMonth()->subMonths($back);
            foreach (range(1, 4 + intdiv(12 - $back, 2)) as $i) {
                $name = $first[mt_rand(0, count($first) - 1)].' '.$last[mt_rand(0, count($last) - 1)];
                $joined = $month->copy()->addDays(mt_rand(0, $back === 0 ? max(0, now()->day - 1) : 27))->setTime(mt_rand(7, 22), mt_rand(0, 59));
                $users->push(User::create([
                    'name' => $name,
                    'email' => strtolower(str_replace(' ', '.', $name)).($users->count() + 1).'@example.com',
                    'login_type' => $types[mt_rand(0, count($types) - 1)],
                    'created_at' => $joined, 'updated_at' => $joined,
                ]));
            }
        }

        return $users;
    }

    /** Walk day by day through the last year: people subscribe, renew, lapse, or rent a single movie. */
    private function payments(Collection $users, Collection $packages, Collection $coupons, Collection $movies, SubscriptionService $subscriptions, RentalService $rentals): void
    {
        $rentable = $movies->filter->isRentable()->values();
        $planWeights = [40, 45, 15]; // Mobile, Premium, Premium 12 Bulan

        for ($day = now()->startOfMonth()->subMonths(11); $day->lte(today()); $day->addDay()) {
            foreach ($users as $user) {
                if ($user->created_at->gt($day->copy()->endOfDay())) {
                    continue;
                }
                $at = $day->copy()->setTime(mt_rand(7, 22), mt_rand(0, 59));
                $endsOn = $user->subscription_ends_on;

                try {
                    if ($endsOn && $endsOn->isSameDay($day) && mt_rand(1, 100) <= 70) {
                        // Renewing on the last day: the new period starts the day after.
                        $subscriptions->subscribe($user, $user->subscriptions()->first()->package, null, $this->reference(), $at);
                        $user->refresh();
                    } elseif ((! $endsOn || $endsOn->lt($day)) && mt_rand(1, 100) <= 3) {
                        $coupon = mt_rand(1, 6) === 1 ? $coupons[mt_rand(0, 1)] : null;
                        $subscriptions->subscribe($user, $packages[$this->pick($planWeights)], $coupon, $this->reference(), $at);
                        $user->refresh();
                    } elseif ((! $endsOn || $endsOn->lt($day)) && mt_rand(1, 100) <= 2) {
                        $rentals->rent($user, $rentable[mt_rand(0, $rentable->count() - 1)], null, $at);
                    }
                } catch (DomainException) {
                    // e.g. a single-use coupon used twice, or the same movie still rented: the app would refuse too.
                }
            }
        }
    }

    /** What people watched. The app writes this; here it is generated in bulk. */
    private function viewing(Collection $users, Collection $movies, Collection $shows): void
    {
        $free = $movies->reject->is_premium->values();
        $episodes = Episode::with('show')->get();
        $rows = [];
        $bookmarks = [];

        foreach ($users as $user) {
            $paid = $user->subscription_ends_on !== null || $user->rentals()->exists();
            $pool = $paid ? $movies : $free;
            $plays = $paid ? mt_rand(6, 14) : mt_rand(1, 5);

            for ($i = 0; $i < $plays; $i++) {
                $episode = mt_rand(1, 4) === 1 ? $episodes[mt_rand(0, $episodes->count() - 1)] : null;
                $content = $episode && ($paid || ! $episode->isPremium()) ? $episode : $pool[mt_rand(0, $pool->count() - 1)];
                $when = Carbon::instance($user->created_at)->max(now()->subDays(120))->addMinutes(mt_rand(0, 120 * 24 * 60));
                $when = $when->min(now());
                $rows[] = [
                    'user_id' => $user->id, 'watchable_type' => $content::class, 'watchable_id' => $content->id,
                    'stopped_at_seconds' => (int) ($content->duration_minutes * 60 * mt_rand(15, 100) / 100),
                    'created_at' => $when, 'updated_at' => $when,
                ];
            }
            foreach ($pool->random(min(3, $pool->count()))->pluck('id')->unique() as $movieId) {
                $bookmarks[] = ['user_id' => $user->id, 'movie_id' => $movieId, 'created_at' => now(), 'updated_at' => now()];
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('watch_history')->insert($chunk);
        }
        DB::table('bookmarks')->insert($bookmarks);
    }

    private function pick(array $weights): int
    {
        $roll = mt_rand(1, array_sum($weights));
        foreach ($weights as $index => $weight) {
            if (($roll -= $weight) <= 0) {
                return $index;
            }
        }

        return 0;
    }

    private function reference(): string
    {
        return 'MID-'.strtoupper(base_convert((string) mt_rand(100000000, 999999999), 10, 36));
    }
}
