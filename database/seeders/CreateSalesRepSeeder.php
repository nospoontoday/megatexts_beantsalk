<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSalesRepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = Branch::find([1]);

        $user = User::create(
            [
                'first_name'        => 'Kirk',
                'last_name'         => 'Bituin',
                'email'             => 'kirkbituin@megatexts.com',
                'username'          => 'kirkbituin',
                'profile_pic'       => 'https://randomuser.me/api/portraits/men/74.jpg',
                'gender'            => 'M',
                'birth_date'        => Carbon::create('02/15/1988'),
                'permanent_address' => '4th MJ Lane 20th Street',
                'password'          => Hash::make('password123'),
                'is_admin'          => false,
            ]
        );

        $user->branches()->attach($branch);

        $address = $user->addresses()->create(
            [
                'present_address'   => '245 Diamond Street Charlotte North Carolina',
            ]
        );

        $contact = $user->contacts()->create(
            [
                'mobile'            => '09102920194',
                'landline'          => '0853400119',
            ]
        );

        $user->assignRole([4]);

// SALES REP 2
        $branch2 = Branch::find([2]);

        $user2 = User::create(
            [
                'first_name'        => 'Rio',
                'last_name'         => 'Malaluan',
                'email'             => 'riomalaluan@megatexts.com',
                'username'          => 'riomalaluan',
                'profile_pic'       => 'https://randomuser.me/api/portraits/men/74.jpg',
                'gender'            => 'M',
                'birth_date'        => Carbon::create('02/15/1988'),
                'permanent_address' => '4th MJ Lane 20th Street',
                'password'          => Hash::make('password123'),
                'is_admin'          => false,
            ]
        );

        $user2->branches()->attach($branch2);

        $address = $user2->addresses()->create(
            [
                'present_address'   => '245 Diamond Street Charlotte North Carolina',
            ]
        );

        $contact = $user2->contacts()->create(
            [
                'mobile'            => '09102920194',
                'landline'          => '0853400119',
            ]
        );

        $user2->assignRole([4]);


// SALES REP 3
        $branch3 = Branch::find([3]);

        $user3 = User::create(
            [
                'first_name'        => 'Eliseo',
                'last_name'         => 'Tejada',
                'email'             => 'eliseotejada@megatexts.com',
                'username'          => 'eliseotejada',
                'profile_pic'       => 'https://randomuser.me/api/portraits/men/74.jpg',
                'gender'            => 'M',
                'birth_date'        => Carbon::create('02/15/1988'),
                'permanent_address' => '4th MJ Lane 20th Street',
                'password'          => Hash::make('password123'),
                'is_admin'          => false,
            ]
        );

        $user3->branches()->attach($branch3);

        $address = $user3->addresses()->create(
            [
                'present_address'   => '245 Diamond Street Charlotte North Carolina',
            ]
        );

        $contact = $user3->contacts()->create(
            [
                'mobile'            => '09102920194',
                'landline'          => '0853400119',
            ]
        );

        $user3->assignRole([4]);
    }
}
