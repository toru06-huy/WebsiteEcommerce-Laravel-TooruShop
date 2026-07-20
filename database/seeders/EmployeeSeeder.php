<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin=DB::table('users')->insertGetId([
            'email' => 'admin@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('12345678'),
            'email_verified_at' => Carbon::now(),
            'fullName' => 'Admin',
            'sex' => 'Nam',
            'birthday' => Carbon::now()->toDateString(),
            'role' => 'Admin',
            'IsActive' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employees')->insert([
            'userID'       => $admin,
            'employeeCode' => 'NV000',
            'positionID'   => 1,
            'salary'       => 8000000.00,
            'hireDate'     => '2024-01-10',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        DB::table('addresses')->insert([
            'userID'        => $admin,
            'city'          => "Hồ Chí Minh",
            'district'      => "6" ,
            'ward'          => "Tân Hòa Đông", 
            'addressDetail' => "241/27B7 Tân Hòa Đông",
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        //Owner
        $owner=DB::table('users')->insertGetId([
            'email' => 'dh52200779@student.stu.edu.vn',
            'phone' => '0369462738',
            'password' => Hash::make('12345678'),
            'email_verified_at' => Carbon::now(),
            'fullName' => 'Owner',
            'sex' => 'Nam',
            'birthday' => Carbon::now()->toDateString(),
            'role' => 'Owner',
            'IsActive' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('employees')->insert([
            'userID'       => $owner,
            'employeeCode' => 'NV001',
            'positionID'   => 1,
            'salary'       => 8000000.00,
            'hireDate'     => '2024-01-10',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        DB::table('addresses')->insert([
            'userID'        => $owner,
            'city'          => "Hồ Chí Minh",
            'district'      => "6" ,
            'ward'          => "Tân Hòa Đông", 
            'addressDetail' => "241/27B7 Tân Hòa Đông",
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
// Employees
        $userID1 = DB::table('users')->insertGetId([
            'email'             => 'lan@gmail.vn',
            'phone'             => '0909111222',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => Carbon::now(),
            'fullName'          => 'Trần Thị Lan',
            'sex'               => 'Nữ',
            'birthday'          => '1998-05-15',
            'role'              => 'Employee',
            'IsActive'          => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        // Tạo bản ghi Employee liên kết với User vừa tạo
        DB::table('employees')->insert([
            'userID'       => $userID1,
            'employeeCode' => 'NV002',
            'positionID'   => 2,
            'salary'       => 8000000.00,
            'hireDate'     => '2024-01-10',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        DB::table('addresses')->insert([
            'userID'        => $userID1,
            'city'          => "Hồ Chí Minh",
            'district'      => "7" ,
            'ward'          => "Tân Kiểng", 
            'addressDetail' => "241 Bùi Đình Túy",
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $userID2 = DB::table('users')->insertGetId([
            'email'             => 'hoang01@gmail.vn',
            'phone'             => '0909111223',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => Carbon::now(),
            'fullName'          => 'Trần Văn Hoàng',
            'sex'               => 'Nam',
            'birthday'          => '1998-05-15',
            'role'              => 'Employee',
            'IsActive'          => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        // Tạo bản ghi Employee liên kết với User vừa tạo
        DB::table('employees')->insert([
            'userID'       => $userID2,
            'employeeCode' => 'NV003',
            'positionID'   => 2,
            'salary'       => 8000000.00,
            'hireDate'     => '2024-01-10',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        DB::table('addresses')->insert([
            'userID'        => $userID2,
            'city'          => "Hồ Chí Minh",
            'district'      => "7" ,
            'ward'          => "Tân Kiểng", 
            'addressDetail' => "241 Bùi Đình Túy",
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
