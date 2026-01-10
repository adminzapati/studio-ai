<?php
try {
    $u = \App\Models\User::firstOrCreate(
        ['email' => 'admin@studio.ai'], 
        ['name' => 'Admin', 'password' => bcrypt('password')]
    );
    // Check if role exists first
    if (\Spatie\Permission\Models\Role::where('name', 'Admin')->exists()) {
        $u->assignRole('Admin');
        echo "Admin user created and assigned role.\n";
    } else {
        echo "Admin role not found. Please run RolesAndPermissionsSeeder first.\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
