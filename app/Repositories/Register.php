<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Kyc;
use App\Models\User;
use App\Repositories\Interfaces\IRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Register implements IRepository
{
    /**
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param string $password
     * @param Carbon $dob
     * @param string $documentType
     * @param string $documentFile
     * @param string $address
     * @param string $city
     * @param string $zipCode
     * @param int $countryId
     * @param string|null $note
     * @return bool
     */
    public static function signUp(
        string $name,
        string $phone,
        string $email,
        string $password,
        Carbon $dob,
        string $documentType,
        string $documentFile,
        string $address,
        string $city,
        string $zipCode,
        int $countryId,
        ?string $note
    ): bool {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make($password),
                'note' => $note,
            ]);

            Address::create([
                'user_id' => $user->id,
                'country_id' => $countryId,
                'address' => $address,
                'city' => $city,
                'zip_code' => $zipCode,
            ]);

            Kyc::create([
                'user_id' => $user->id,
                'dob' => $dob,
                'document_type' => $documentType,
                'document_file' => $documentFile,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }

        return true;
    }
}
