<?php

namespace Tests\Feature;

use App\Models\OneTimeBreak;
use App\Models\Service;
use App\Models\WeeklyAvailability;
use App\Models\WeeklyBreak;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use DatabaseTransactions; // Use transactions to rollback database changes made during the tests
    
    public function testGetMethodReturnsSuccessResponse()
    {
        $currentDayOfWeek = Carbon::now()->dayOfWeek;

        // Calculate the date based on the current day of the week
        if ($currentDayOfWeek === Carbon::SUNDAY) {
            $date = Carbon::today()->format('Y-m-d');
        } else {
            $nextSunday = Carbon::now()->next(Carbon::SUNDAY);
            $date = $nextSunday->format('Y-m-d');
        }

        $service = Service::create([
            'name' => 'xyz',
            'buffer_time' => '00:15:00',
            'duration' => '00:30:00',
            'scheduling_window' => 7,
            'max_appointments_per_slot' => 1
        ]);

        WeeklyAvailability::create([
            'day_of_week' => '0',
            'start_time' => '09:00:00',
            'end_time' => '22:00:00',
            'service_id' => $service->id
        ]);

        WeeklyBreak::create([
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'service_id' => $service->id,
        ]);


        WeeklyBreak::create( [
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'service_id' => $service->id,
        ]);

        OneTimeBreak::create([
            'start_time' => $date . ' 11:00:00',
            'end_time' => $date . ' 12:00:00',
            'service_id' => $service->id,
        ]);

        $response = $this->get(route('appointment.get', [
            'date' => $date,
            'service_id' => $service->id,
        ]));

        $response = $this->get('/appointments/get?service_id='.$service->id.'&date='.$date);

        $response->assertStatus(200)
            ->assertJson(['data' => [
                'day_of_week' => 0,
                'start_time' => '09:00:00',
                'end_time' => '22:00:00',
                'service_id' => $service->id,
                'is_disabled' => 0,
                'available_slots' => [
                    [
                        'start_time' => '10:00:00',
                        'end_time' => '10:30:00'
                    ],
                    [
                        'start_time' => '12:00:00',
                        'end_time' => '12:30:00'
                    ],
                    [
                        'start_time' => '14:00:00',
                        'end_time' => '14:30:00'
                    ],
                    [
                        'start_time' => '14:45:00',
                        'end_time' => '15:15:00'
                    ],
                    [
                        'start_time' => '15:30:00',
                        'end_time' => '16:00:00'
                    ],
                    [
                        'start_time' => '16:15:00',
                        'end_time' => '16:45:00'
                    ],
                    [
                        'start_time' => '17:00:00',
                        'end_time' => '17:30:00'
                    ],
                    [
                        'start_time' => '17:45:00',
                        'end_time' => '18:15:00'
                    ],
                    [
                        'start_time' => '18:30:00',
                        'end_time' => '19:00:00'
                    ],
                    [
                        'start_time' => '19:15:00',
                        'end_time' => '19:45:00'
                    ],
                    [
                        'start_time' => '20:00:00',
                        'end_time' => '20:30:00'
                    ],
                    [
                        'start_time' => '20:45:00',
                        'end_time' => '21:15:00'
                    ],
                    [
                        'start_time' => '21:30:00',
                        'end_time' => '22:00:00'
                    ]
                ],
                'service' => [
                    'id' => $service->id,
                    'buffer_time' => '00:15:00',
                    'duration' => '00:30:00',
                    'scheduling_window' => 7,
                    'max_appointments_per_slot' => 1,
                    'weekly_breaks' => [
                        [
                            'start_time' => '09:00:00',
                            'end_time' => '10:00:00',
                            'service_id' => $service->id
                        ],
                        [
                            'start_time' => '13:00:00',
                            'end_time' => '14:00:00',
                            'service_id' => $service->id
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function testStoreMethodCreatesBooking()
    {
        $currentDayOfWeek = Carbon::now()->dayOfWeek;

        // Calculate the date based on the current day of the week
        if ($currentDayOfWeek === Carbon::SUNDAY) {
            $date = Carbon::today()->format('Y-m-d');
        } else {
            $nextSunday = Carbon::now()->next(Carbon::SUNDAY);
            $date = $nextSunday->format('Y-m-d');
        }
        $data = $this->testSeeder($date);

        $response = $this->post(route('appointment.store'), $data);

        $response = $this->post(('/appointments/store'), $data);

        $response->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "start_time" => $date . " 10:00:00",
                        "end_time" => $date . " 10:30:00",
                        "weekly_availability_id" => $response->json()['data'][0]['weekly_availability_id'],
                    ]
                ]
            ]);
    }

    public function testStoreMethodReturnsErrorWhenSlotAlreadyBookedToMaxCount()
    {
        $currentDayOfWeek = Carbon::now()->dayOfWeek;

        // Calculate the date based on the current day of the week
        if ($currentDayOfWeek === Carbon::SUNDAY) {
            $date = Carbon::today()->format('Y-m-d');
        } else {
            $nextSunday = Carbon::now()->next(Carbon::SUNDAY);
            $date = $nextSunday->format('Y-m-d');
        }
        $data = $this->testSeeder($date);

        $response = $this->post(route('appointment.store'), $data);

        $response = $this->post('/appointments/store', $data);
        $response = $this->post('/appointments/store', $data);
        $response = $this->post('/appointments/store', $data);

        $response->assertStatus(400)
            ->assertJson([
                "message" => "Slots cannot be booked due to max count allowed.",
            ]);
    }

    public function testStoreMethodReturnsErrorWhenSlotIsNotValid()
    {
        $currentDayOfWeek = Carbon::now()->dayOfWeek;

        // Calculate the date based on the current day of the week
        if ($currentDayOfWeek === Carbon::SUNDAY) {
            $date = Carbon::today()->format('Y-m-d');
        } else {
            $nextSunday = Carbon::now()->next(Carbon::SUNDAY);
            $date = $nextSunday->format('Y-m-d');
        }
        $data = $this->testSeeder($date, '11:00:00');

        $response = $this->post(route('appointment.store'), $data);

        $response = $this->post('/appointments/store', $data);

        $response->assertStatus(400)
            ->assertJson([
                "message" => "At least one slot is not valid.",
            ]);
    }

    private function testSeeder($date, $endTime = '10:30:00')
    {
        $service = $this->testBaseSeeder($date);

        $weeklyAvailability = WeeklyAvailability::create([
            'day_of_week' => '0',
            'start_time' => '09:00:00',
            'end_time' => '22:00:00',
            'service_id' => $service->id
        ]);

        $data = [
            [
                'weekly_availability_id' => $weeklyAvailability->id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'date' => $date,
                'start_time' => '10:00:00',
                'end_time' => $endTime,
            ]
        ];

        return $data;
    }

    private function testBaseSeeder($date)
    {
        $service = Service::create([
            'name' => 'xyz',
            'buffer_time' => '00:15:00',
            'duration' => '00:30:00',
            'scheduling_window' => 7,
            'max_appointments_per_slot' => 2
        ]);

        WeeklyBreak::create([
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'service_id' => $service->id,
        ]);


        WeeklyBreak::create( [
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'service_id' => $service->id,
        ]);
       

        OneTimeBreak::create([
            'start_time' => $date . ' 11:00:00',
            'end_time' => $date . ' 12:00:00',
            'service_id' => $service->id,
        ]);

        return $service;
    }
}
