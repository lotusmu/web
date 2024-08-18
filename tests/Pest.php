<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function refreshTable(string $tableName, ?string $connection = null): void
{
    $db = $connection ? DB::connection($connection) : DB::connection();

    $isSqlServer = $db->getDriverName() === 'sqlsrv';

    if ($isSqlServer) {
        $db->statement('EXEC sp_MSforeachtable "ALTER TABLE ? NOCHECK CONSTRAINT ALL"');

        $db->table($tableName)->delete();

        $db->statement('EXEC sp_MSforeachtable "ALTER TABLE ? WITH CHECK CHECK CONSTRAINT ALL"');
    } else {
        $db->statement('SET FOREIGN_KEY_CHECKS=0;');
        $db->table($tableName)->truncate();
        $db->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

function getRouteKeyFor($record)
{
    return $record->getRouteKey();
}

function fakeUsername(): string
{
    return substr(fake()->userName, 0, 10);
}

function fakeEmail(): string
{
    return fake()->unique()->safeEmail();
}

function fakePassword()
{
    return fake()->password(4, 10);
}
