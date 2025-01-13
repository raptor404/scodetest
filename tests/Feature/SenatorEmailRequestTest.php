<?php

namespace Tests\Feature;

use App\Models\Senator;
use Tests\TestCase;

class SenatorEmailRequestTest extends TestCase
{
    public function test_senator_email_request_base_case()
    {
        $this->withoutMiddleware();
        $senatorFound = Senator::query()->select('id')->first();
        if ($senatorFound === null) {
            echo 'no senators in db, skipping test';
            return true;
        }

        $this->post('/api/senator/email', [
            'senator_id' => $senatorFound->id,
            'last_name' => 't',
            'email' => 'test@test.com',
            'message' => 'test message',

        ])->assertStatus(200);
    }

     public function test_bad_input()
     {
         $this->withoutMiddleware();
         $senatorFound = Senator::query()->select('id')->first();
         if ($senatorFound === null) {
             echo 'no senators in db, skipping test';
             return true;
         }

         $badInputs = [
             [//empty
             'senator_id' => '',
             'last_name' => '',
             'email' => '',
             'message' => '',
             ],
             [//missing each required 1
                 'senator_id' => '',
                 'last_name' => 't',
                 'email' => 'test@test.com',
                 'message' => 'test message',
             ],
             [//missing each required 2
                 'senator_id' =>$senatorFound->id,
                 'last_name' => '',
                 'email' => 'test@test.com',
                 'message' => 'test message',
             ],
             [//missing each required 3
                 'senator_id' =>$senatorFound->id,
                 'last_name' => 't',
                 'email' => '',
                 'message' => 'test message',
             ],
             [//missing each required 4
                 'senator_id' =>$senatorFound->id,
                 'last_name' => 't',
                 'email' => 'test@test.com',
                 'message' => '',
             ],
         ];


         foreach($badInputs as $badInput) {
             $this->post('/api/senator/email', $badInput)->assertStatus(422);
         }
         $this->assertTrue(true, 'bad inputs tested');
     }
}
