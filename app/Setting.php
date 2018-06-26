<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'enable_backup' => 'boolean',
        'enable_data_reset' => 'boolean',
        'public_online_users' => 'boolean',
        'enable_online_users' => 'boolean',
        'public_server_status' => 'boolean',
        'enable_server_status' => 'boolean',
        'enable_authorized_reseller' => 'boolean',
        'public_authorized_reseller' => 'boolean',
        'enable_public_registration' => 'boolean',
        'enable_account_creation' => 'boolean',
        'backup_gzip_database_dump' => 'boolean',
    ];
}
