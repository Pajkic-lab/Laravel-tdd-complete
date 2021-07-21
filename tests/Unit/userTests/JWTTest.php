<?php

namespace Tests\Unit\userTests;

use Carbon\Carbon;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JWTTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_jwt_decode_before_expiration_time()
    {
        $key = config('app.JWT_SECRET');
        $payload = array(
            "id" => 1,
            "exp" => Carbon::now()->addSeconds(2)->timestamp,
        );
        $jwt = JWT::encode($payload, $key);

        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $this->assertEquals($decoded->id, 1);
    }

    /** @test */
    public function a_jwt_decode_after_expiration_time()
    {
        $this->expectException(ExpiredException::class);

        $key = config('app.JWT_SECRET');
        $payload = array(
            "id" => 1,
            "exp" => Carbon::now()->addSeconds(1)->timestamp,
        );
        $jwt = JWT::encode($payload, $key);

        sleep(2);

        JWT::decode($jwt, $key, array('HS256'));
    }
}


