<?php

use App\Models\ProjectStatus;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectStatus::query()->truncate();
        $data = [
            ['name' => 'Open Project',
            'label_color' => '#FFF845',
            'description' => 'If your project recently created this status can automaticly added to your project'],
            ['name' => 'In Progress',
            'label_color' => '#1AFAEB',
            'description' => 'If your project has been in progress you can add this to your status project'],
            ['name' => 'Ready To Check',
            'label_color' => '#00A405',
            'description' => 'If your project has been ready but you waiting feedback for your client you can add this to your status project'],
            ['name' => 'Revise',
            'label_color' => '#FB160A',
            'description' => 'If your project get revise from client you can add this to your status project'],
            ['name' => 'Approved',
            'label_color' => '#63ED7A',
            'description' => 'If your project has been approved you can add this to your status project'],
            ['name' => 'Completed',
            'label_color' => '#6777EF',
            'description' => 'If your project is completed you can add this to your status project'],
            ['name' => 'Sent',
            'label_color' => '#F14604',
            'description' => 'If your project has been sent you can add this to your status project'],
            ['name' => 'Cancel',
            'label_color' => '#5C6D7A',
            'description' => 'If your project has been canceled'],
            ['name' => 'Trash',
            'label_color' => '#FB160A',
            'description' => 'If your project has been deleted'],
        ];
        foreach ($data as $value) {
            $projectStatus = new ProjectStatus();
            $projectStatus->fill($value);
            $projectStatus->save();
        }
    }
}
