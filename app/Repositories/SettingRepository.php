<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function all()
    {
        return Setting::latest()->get();
    }

    public function create(array $data)
    {
        return Setting::create($data);
    }
}