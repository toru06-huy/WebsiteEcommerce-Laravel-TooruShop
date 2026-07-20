<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = DB::table('users')->insertGetId([
            'email'             => 'kaitoru06@gmail.com',
            'phone'             => '0931462157',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => Carbon::now(),
            'fullName'          => 'Nguyễn Hoàng Quốc Huy',
            'sex'               => 'Nam',
            'birthday'          => '2004-07-10',
            'role'              => 'Customer',
            'IsActive'          => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('addresses')->insert([
            'userID'        => $user1,
            'city'          => "Hồ Chí Minh",
            'district'      => "Bình Thạnh",
            'ward'          => "Bình Tân",
            'addressDetail' => "56 Hoàng Hoa Thám",
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('membership_tiers')->insert([
            'userID'        => $user1,
            'tier'          => "Bronze",
            'totalSpent'    => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        $user2 = DB::table('users')->insertGetId([
            'email'             => 'huongthao23112004@gmail.com',
            'phone'             => '0922222222',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => Carbon::now(),
            'fullName'          => 'Nguyễn Huơng Thảo',
            'sex'               => 'Nữ',
            'birthday'          => '2004-11-23',
            'role'              => 'Customer',
            'IsActive'          => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('addresses')->insert([
            'userID'        => $user2,
            'city'          => "Hồ Chí Minh",
            'district'      => "Bình Thạnh",
            'ward'          => "Bình Tân",
            'addressDetail' => "56 Hoàng Hoa Thám",
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('membership_tiers')->insert([
            'userID'        => $user2,
            'tier'          => "Bronze",
            'totalSpent'    => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        $user3 = DB::table('users')->insertGetId([
            'email'             => 'haooa@gmail.com',
            'phone'             => '0922222223',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => Carbon::now(),
            'fullName'          => 'Nguyễn Văn Hào',
            'sex'               => 'Nam',
            'birthday'          => '1999-01-20',
            'role'              => 'Customer',
            'IsActive'          => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('addresses')->insert([
            'userID'        => $user3,
            'city'          => "Hồ Chí Minh",
            'district'      => "Bình Thạnh",
            'ward'          => "Bình Tân",
            'addressDetail' => "56 Hoàng Hoa Thám",
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        DB::table('membership_tiers')->insert([
            'userID'        => $user3,
            'tier'          => "Bronze",
            'totalSpent'    => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
