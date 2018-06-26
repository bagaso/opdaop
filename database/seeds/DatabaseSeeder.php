<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date_now = Carbon::now();
        // $this->call(UsersTableSeeder::class);
        DB::table('settings')->insert([
            'site_name' => 'VPN Panel',
            'created_at' => $date_now,
        ]);

        DB::table('pages')->insert([
            'name' => 'Index',
            'content' => 'Default Content.',
            'slug_url' => 'index',
            'is_public' => 1,
            'created_at' => $date_now,
        ]);

        $groups = [
            [
                'class' => 'danger',
                'name' => 'Administrator'
            ],
            [
                'class' => 'warning',
                'name' => 'Sub-Admin'
            ],
            [
                'class' => 'primary',
                'name' => 'Reseller'
            ],
            [
                'class' => 'primary',
                'name' => 'Sub-Reseller'
            ],
            [
                'class' => 'info',
                'name' => 'Client'
            ]
        ];

        DB::table('groups')->insert($groups);

        $statuses = [
            [
                'class' => 'default',
                'name_set' => 'Deactivate',
                'name_get' => 'Deactivated'
            ],
            [
                'class' => 'success',
                'name_set' => 'Activate',
                'name_get' => 'Activated'
            ],
            [
                'class' => 'danger',
                'name_set' => 'Suspend',
                'name_get' => 'Suspended'
            ]
        ];

        DB::table('statuses')->insert($statuses);

        $subscriptions = [
            [
                'class' => 'info',
                'name' => 'Bronze',
                'cost' => 1,
                'data' => 0,
                'device' => 1,
                'min_credits' => 1,
                'max_credits' => 1,
                'dl_speed_openvpn' => 1,
                'up_speed_openvpn' => 1,
                'is_enable' => 1,
                'is_public' => 1,
            ],
            [
                'class' => 'success',
                'name' => 'Silver',
                'cost' => 1,
                'data' => 0,
                'device' => 1,
                'min_credits' => 1,
                'max_credits' => 1,
                'dl_speed_openvpn' => 1,
                'up_speed_openvpn' => 1,
                'is_enable' => 1,
                'is_public' => 1,
            ],
            [
                'class' => 'warning',
                'name' => 'Gold',
                'cost' => 1,
                'data' => 0,
                'device' => 1,
                'min_credits' => 1,
                'max_credits' => 1,
                'dl_speed_openvpn' => 1,
                'up_speed_openvpn' => 1,
                'is_enable' => 1,
                'is_public' => 1,
            ],
            [
                'class' => 'danger',
                'name' => 'Diamond',
                'cost' => 1,
                'data' => 0,
                'device' => 1,
                'min_credits' => 1,
                'max_credits' => 1,
                'dl_speed_openvpn' => 1,
                'up_speed_openvpn' => 1,
                'is_enable' => 1,
                'is_public' => 1,
            ]
        ];

        DB::table('subscriptions')->insert($subscriptions);

        $server_accesses = [
            [
                'class' => 'info',
                'name' => 'Free',
                'max_users' => 50,
                'max_device' => 1,
                'is_paid' => 0,
                'is_private' => 0,
                'multi_login' => 0,
                'is_enable' => 1,
                'is_public' => 0,
            ],
            [
                'class' => 'primary',
                'name' => 'Premium',
                'max_users' => 50,
                'max_device' => 1,
                'is_paid' => 0,
                'is_private' => 0,
                'multi_login' => 0,
                'is_enable' => 1,
                'is_public' => 0,
            ],
            [
                'class' => 'success',
                'name' => 'VIP',
                'max_users' => 50,
                'max_device' => 1,
                'is_paid' => 0,
                'is_private' => 0,
                'multi_login' => 0,
                'is_enable' => 1,
                'is_public' => 0,
            ],
            [
                'class' => 'danger',
                'name' => 'Private',
                'max_users' => 50,
                'max_device' => 1,
                'is_paid' => 0,
                'is_private' => 0,
                'multi_login' => 0,
                'is_enable' => 1,
                'is_public' => 0,
            ]
        ];

        DB::table('server_accesses')->insert($server_accesses);

        DB::table('users')->insert([
            'username' => 'admin101',
            'password' => bcrypt('admin101'),
            'service_password' => 'admin101',
            'email' => 'admin@panel.com',
            'fullname' => 'Admin',
            'group_id' => 1,
            'status_id' => 2,
            'password_openvpn' => 'admin101',
            'password_ssh' => 'admin101',
            'value' => 'admin101',
            'created_at' => $date_now,
        ]);
    }
}
