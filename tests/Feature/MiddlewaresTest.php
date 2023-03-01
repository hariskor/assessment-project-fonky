<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MiddlewaresTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_middleware(): void
    {
        $user = $this->setUpUser(true);
        $response = $this->actingAs($user)
            ->get('seller/sales');

        $response->assertStatus(200);
    }
    
    public function test_seller_middleware_blocks(): void
    {
        $user = $this->setUpUser();
        $response = $this->actingAs($user)
            ->get('seller/sales');

        $response->assertStatus(302);
    }

    /**
     * @param bool $attachSeller
     * @return User
     */
    private function setUpUser(bool $attachSeller = false): User
    {
        $user = new User([
            'name' => 'username',
            'email' => 'email@email.com',
            'password' => 'password'
        ]);
        $user->save();

        if($attachSeller){
            $seller = new Seller(['user_id'=>$user->id]);
            $seller->save();    
        }
        return $user;
    }
}
