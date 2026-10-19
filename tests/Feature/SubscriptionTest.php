<?php

namespace Tests\Feature;

use App\Enums\AmountType;
use App\Models\Coupon;
use App\Models\Package;
use App\Models\User;
use App\Services\SubscriptionService;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-03-10 10:00');
    }

    private function subscribe(User $user, Package $package, ?Coupon $coupon = null)
    {
        return app(SubscriptionService::class)->subscribe($user, $package, $coupon);
    }

    private function coupon(array $overrides = []): Coupon
    {
        return Coupon::create($overrides + [
            'code' => 'HEMAT', 'amount_type' => AmountType::Percent, 'amount' => 10,
            'starts_on' => '2026-01-01', 'ends_on' => '2026-12-31', 'single_use' => false,
        ]);
    }

    public function test_a_first_subscription_starts_today(): void
    {
        $user = User::factory()->create();

        $subscription = $this->subscribe($user, Package::factory()->create(['duration_months' => 1]));

        $this->assertSame('2026-03-10', $subscription->starts_on->toDateString());
        $this->assertSame('2026-04-09', $subscription->ends_on->toDateString());
        $this->assertSame('2026-04-09', $user->fresh()->subscription_ends_on->toDateString());
    }

    public function test_renewing_early_adds_the_time_after_the_current_end(): void
    {
        $user = User::factory()->subscribedUntil('2026-03-20')->create();

        $subscription = $this->subscribe($user, Package::factory()->create(['duration_months' => 12]));

        $this->assertSame('2026-03-21', $subscription->starts_on->toDateString());
        $this->assertSame('2027-03-20', $user->fresh()->subscription_ends_on->toDateString());
    }

    public function test_renewing_after_it_lapsed_starts_again_today(): void
    {
        $user = User::factory()->subscribedUntil('2026-01-31')->create();

        $subscription = $this->subscribe($user, Package::factory()->create());

        $this->assertSame('2026-03-10', $subscription->starts_on->toDateString());
    }

    public function test_coupons_take_a_percentage_or_a_fixed_amount_off(): void
    {
        $package = Package::factory()->create(['price' => 35000]);

        $percent = $this->subscribe(User::factory()->create(), $package, $this->coupon());
        $fixed = $this->subscribe(User::factory()->create(), $package, $this->coupon(['code' => 'POTONG', 'amount_type' => AmountType::Fixed, 'amount' => 5000]));

        $this->assertSame([3500, 31500], [$percent->discount, $percent->amount]);
        $this->assertSame([5000, 30000], [$fixed->discount, $fixed->amount]);
    }

    public function test_a_fixed_coupon_never_makes_the_price_negative(): void
    {
        $subscription = $this->subscribe(User::factory()->create(), Package::factory()->create(['price' => 15000]), $this->coupon(['amount_type' => AmountType::Fixed, 'amount' => 50000]));

        $this->assertSame(0, $subscription->amount);
    }

    public function test_a_single_use_coupon_works_once_per_user(): void
    {
        $user = User::factory()->create();
        $coupon = $this->coupon(['single_use' => true]);
        $this->subscribe($user, Package::factory()->create(), $coupon);

        $this->expectException(DomainException::class);
        $this->subscribe($user, Package::factory()->create(), $coupon);
    }

    public function test_an_expired_coupon_is_refused_and_nothing_is_saved(): void
    {
        $user = User::factory()->create();

        try {
            $this->subscribe($user, Package::factory()->create(), $this->coupon(['ends_on' => '2026-02-28']));
            $this->fail('An expired coupon was accepted.');
        } catch (DomainException) {
            $this->assertSame(0, $user->subscriptions()->count());
            $this->assertNull($user->fresh()->subscription_ends_on);
        }
    }

    public function test_an_inactive_plan_cannot_be_sold(): void
    {
        $this->expectException(DomainException::class);
        $this->subscribe(User::factory()->create(), Package::factory()->create(['is_active' => false]));
    }

    public function test_the_form_reports_the_reason_when_a_coupon_is_refused(): void
    {
        $user = User::factory()->create();
        $coupon = $this->coupon(['ends_on' => '2026-02-28']);

        $this->post(route('subscriptions.store'), ['user_id' => $user->id, 'package_id' => Package::factory()->create()->id, 'coupon_id' => $coupon->id])
            ->assertSessionHasErrors('coupon_id');
    }
}
