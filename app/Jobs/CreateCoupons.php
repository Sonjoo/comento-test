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

    protected $group_id;

    /**
     * Create a new job instance.
     * @param App\Dtos\CouponDTO
     * @param int
     * @return void
     */
    public function __construct(CouponDTO $couponBaseInfo, int $group_id)
    {
      $this->couponBaseInfo = $couponBaseInfo;
      $this->group_id = $group_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      //만약의 오류를 위한 transaction
      DB::transaction(function () {
        CouponService::loadWithLength($this->couponBaseInfo->length)
          ->generateCouponCodes(
            $this->couponBaseInfo->prefix,
            ['id' => '', 'coupon_group_id' => $this->group_id, 'created_at' => now()]
          );
      });
    }
}
