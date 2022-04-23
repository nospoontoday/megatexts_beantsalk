<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Carbon\Carbon;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = Branch::find([1,2,3]);

        $user = User::create(
            [
                'first_name'        => 'Jane',
                'last_name'         => 'Doe',
                'email'             => 'superuser@megatexts.com',
                'username'          => 'janedoe',
                'profile_pic'       => 'https://randomuser.me/api/portraits/men/75.jpg',
                'gender'            => 'F',
                'birth_date'        => Carbon::create('02/08/1988'),
                'permanent_address' => '2262 James Martin Circle Columbus Ohio',
                'password'          => Hash::make('password123'),
                'is_admin'          => true,
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

        $role = Role::create(['name' => 'Administrator']);

        Role::create(['name' => 'Warehouse Admin']);
        Role::create(['name' => 'Senior Sales Representative']);
        Role::create(['name' => 'Sales Representative']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
