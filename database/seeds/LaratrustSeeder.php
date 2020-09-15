<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\Sekolah;
class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->truncateLaratrustTables();
        $sekolah = Sekolah::updateOrCreate(
            ['sekolah_id' => Str::uuid()],
            [
                'npsn' => 12345678,
                'nama' => 'SEKOLAH CONTOH',
                'status_sekolah' => 2,
            ]
        );
        $config = config('laratrust_seeder.roles_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Role::firstOrCreate([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = \App\Permission::firstOrCreate([
                        'name' => $module . '-' . $permissionValue,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            if(Config::get('laratrust_seeder.create_users')) {
                $this->command->info("Creating '{$key}' user");
                if($key == 'admin'){
                    $user = \App\User::create([
                        'name' => 'Achmadi',
                        'username' => 'masadi',
                        'email' => 'masadi.com@gmail.com',
                        'password' => bcrypt('3l3ktr4&cyber')
                    ]);
                    $user->attachRole($role);
                } else if($key == 'sekolah'){
                    $user = \App\User::create([
                        'name' => $sekolah->nama,
                        'username' => $sekolah->npsn,
                        'sekolah_id' => $sekolah->sekolah_id,
                        'email' => $key.'@apmsmk.net',
                        'password' => bcrypt('12345678')
                    ]);
                    $user->attachRole($role);
                } else {
                // Create default user for each role
                    $user = \App\User::create([
                        'name' => ucwords(str_replace('_', ' ', $key)),
                        'username' => strtolower(str_replace(' ', '_', $key)),
                        'email' => $key.'@apmsmk.net',
                        'password' => bcrypt('12345678')
                    ]);
                    $user->attachRole($role);
                }
            }

        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        $rolesTable = (new \App\Role)->getTable();
        $permissionsTable = (new \App\Permission)->getTable();
        if(Config::get('laratrust_seeder.truncate_tables')) {
            DB::statement("TRUNCATE TABLE {$permissionsTable} CASCADE");
            DB::statement("TRUNCATE TABLE {$rolesTable} CASCADE");
        }
        if(Config::get('laratrust_seeder.truncate_tables') && Config::get('laratrust_seeder.create_users')) {
            $usersTable = (new \App\User)->getTable();
            DB::statement("TRUNCATE TABLE {$usersTable} CASCADE");
        }
        Schema::enableForeignKeyConstraints();
    }
}
