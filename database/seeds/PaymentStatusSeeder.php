<?php

use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'DP',
            'label_color' => '#63ed7a',
            'description' => ''],
            ['name' => 'CASH',
            'label_color' => '#3abaf4',
            'description' => '']
        ];
        foreach ($data as $value) {
            $paymentStatus = new PaymentStatus();
            $paymentStatus->fill($value);
            $paymentStatus->save();
        }
        $data = [
            ['name' => 'TRANSFER',
            'label_color' => '#63ed7a',
            'description' => '']
        ];
        foreach ($data as $value) {
            $paymentMethod = new PaymentMethod();
            $paymentMethod->fill($value);
            $paymentMethod->save();
        }
    }
}
