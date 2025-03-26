<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

<<<<<<<< HEAD:database/migrations/2025_03_25_210026_add_column_to_users.php
return new class extends Migration
========
class AddIsApprovedToUsersTable extends Migration
>>>>>>>> c43a6483b77f954af99077d3a686d2f87c2384d4:database/migrations/2025_03_25_210208_add_is_approved_to_users_table.php
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
<<<<<<<< HEAD:database/migrations/2025_03_25_210026_add_column_to_users.php
            $table->timestamp('banned_at')->nullable();
========
            $table->boolean('is_approved')->nullable()->after('email');
>>>>>>>> c43a6483b77f954af99077d3a686d2f87c2384d4:database/migrations/2025_03_25_210208_add_is_approved_to_users_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
<<<<<<<< HEAD:database/migrations/2025_03_25_210026_add_column_to_users.php
            $table->dropColumn('banned_at');
========
            $table->dropColumn('is_approved');
>>>>>>>> c43a6483b77f954af99077d3a686d2f87c2384d4:database/migrations/2025_03_25_210208_add_is_approved_to_users_table.php
        });
    }
};
