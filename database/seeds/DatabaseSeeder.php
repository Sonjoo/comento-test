<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $users = factory(App\User::class, 4)->create();
      $groups = factory(App\CouponGroup::class, 100)->create();
      $users->each(function ($user) use ($groups) {
        $groups->each(function ($group) use ($user) {
          $group->coupons()->saveMany(factory(App\Coupon::class, 1000)->make(['user_id' => $user->id]));
        });
      });
    }
}
