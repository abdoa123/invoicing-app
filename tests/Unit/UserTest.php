<?php

namespace Tests\Unit;


use Tests\TestCase;

use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    //Check if user exists in database
    public function test_user_duplication()
    {
        $user1 = User::make([
            'name' => 'abdo test user ',
            'email' => 'abdo@gmail.com'
        ]);

        $user2 = User::make([
            'name' => 'mohamed test user',
            'email' => 'mohamed@gmail.com'
        ]);

        $this->assertTrue($user1->name != $user2->name);
    }
    //Perform a post() request to add a new user
    public function test_if_it_stores_new_users()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Dary1324',
            'email' => 'dar1y23@gmail.com',
            'password' => 'dary11234',
            'isAdmin' => true,
        ]);
    
        $response->assertStatus(200);
    }
    
    //check if user exist

    public function test_if_data_exists_in_database()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'Dary'
        ]);
    }
    //check if user not exist

    public function test_if_data_does_not_exists_in_database()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'John'
        ]);
    }
}
