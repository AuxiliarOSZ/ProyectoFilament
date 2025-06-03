<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class UsersImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'role' => $row['role'] ?? null,
            'status' => $row['status'],
            'password' => bcrypt('password'),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255'],
            '*.email' => ['required', 'email', 'max:255'],
            '*.role' => ['nullable', 'string', 'max:255'],
            '*.status' => ['required', 'in:0,1'],
        ];
    }
}
