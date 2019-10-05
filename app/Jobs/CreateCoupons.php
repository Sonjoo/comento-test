<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

use App\Dtos\CouponDTO;

use App\Services\CouponService;

class CreateCoupons implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $couponBaseInfo;

    protected $group_id

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CouponDTO $couponBaseInfo, int $group_id)
    {
      $this->couponBaseInfo = $couponBaseInfo;
      $this->$group_id = $group_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $prefix = $this->couponBaseInfo->prefix;
      $previous_unix = 0;
      $entity_array = ['id' => '', 'group_id' => $this->group_id];
      $coupon_service = CouponService::loadWithLength($this->couponBaseIngo->length);
      DB::transaction(function () {
        $coupon_service->generateCouponCodes($prefix);
      });
    }
}
