<?php

namespace App\Repositories\Otp;

use Illuminate\Support\Facades\Redis;

class OtpRepository
{
    protected string $prefix_forgot = "forgot_password:";
    protected $prefix_reset = "reset_password:";

    public function storeOtp($email, $otp, $type)
    {
        $key = ($type == 'forgot') ? $this->prefix_forgot . $email : $this->prefix_reset . $email;
        Redis::setex($key, 600, $otp);
    }

    public function getOtp($email, $type)
    {
        $key = ($type == 'forgot') ? $this->prefix_forgot . $email : $this->prefix_reset . $email;
        return Redis::get($key);
    }

    public function deleteOtp($email, $type)
    {
        $key = ($type == 'forgot') ? $this->prefix_forgot . $email : $this->prefix_reset . $email;
        Redis::del($key);
    }
}
