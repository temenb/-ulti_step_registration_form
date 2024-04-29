<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Kyc;
use App\Models\User;
use App\Repositories\Interfaces\IRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Register implements IRepository
{
    /**
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param string $password
     * @param string $dob
     * @param string $document_type
     * @param string $document_file
     * @param string $address
     * @param string $city
     * @param string $zip_code
     * @param int $country
     * @param string|null $note
     * @return bool
     */
    static public function signUp(
        string $name,
        string $phone,
        string $email,
        string $password,
        string $dob,
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
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }

        return true;
    }
}
