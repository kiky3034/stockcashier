<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index()
    {
        return view('pages.admin.backups.index');
    }

    public function database(Request $request, ActivityLogService $activityLog): StreamedResponse
    {
        $activityLog->log(
            event: 'database_backup_downloaded',
            description: 'Database backup didownload.',
            subject: null,
            properties: [
                'downloaded_at' => now()->toDateTimeString(),
            ],
            user: $request->user(),
        );

        $database = DB::getDatabaseName();
        $filename = 'stockcashier-backup-' . now()->format('YmdHis') . '.sql';

        return response()->streamDownload(function () use ($database) {
            $pdo = DB::getPdo();

            echo "-- StockCashier Database Backup\n";
            echo "-- Database: {$database}\n";
            echo "-- Generated at: " . now()->toDateTimeString() . "\n\n";
            echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $tableRow) {
                $table = array_values((array) $tableRow)[0];

                echo "-- --------------------------------------------------------\n";
                echo "-- Table structure for table `{$table}`\n";
                echo "-- --------------------------------------------------------\n\n";

                echo "DROP TABLE IF EXISTS `{$table}`;\n";

                $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
                $createSql = array_values((array) $createTable[0])[1];

                echo $createSql . ";\n\n";

                echo "-- Data for table `{$table}`\n\n";

                foreach (DB::table($table)->cursor() as $row) {
                    $rowArray = (array) $row;

                    $columns = array_map(function ($column) {
                        return '`' . str_replace('`', '``', $column) . '`';
                    }, array_keys($rowArray));

                    $values = array_map(function ($value) use ($pdo) {
                        if ($value === null) {
                            return 'NULL';
                        }

                        return $pdo->quote((string) $value);
                    }, array_values($rowArray));

                    echo 'INSERT INTO `' . $table . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ");\n";
                }

                echo "\n";
            }

            echo "SET FOREIGN_KEY_CHECKS=1;\n";
        }, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }
}