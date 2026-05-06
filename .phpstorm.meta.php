<?php
// This file helps IDEs understand Laravel's dynamic relationship methods
// See: https://www.jetbrains.com/help/phpstorm/ide-advanced-metadata.html

namespace PHPSTORM_META {
    use App\Models\User;

    override(
        User::assignedClasses(),
        type(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class)
    );

    override(
        User::enrolledClasses(),
        type(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class)
    );

    override(
        User::classes(),
        type(\Illuminate\Database\Eloquent\Relations\HasMany::class)
    );

    override(
        User::qrCodes(),
        type(\Illuminate\Database\Eloquent\Relations\HasMany::class)
    );

    override(
        User::attendanceRecords(),
        type(\Illuminate\Database\Eloquent\Relations\HasMany::class)
    );

    override(
        User::logs(),
        type(\Illuminate\Database\Eloquent\Relations\HasMany::class)
    );
}
