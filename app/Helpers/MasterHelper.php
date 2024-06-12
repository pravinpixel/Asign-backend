<?php

namespace App\Helpers;

use DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;

class MasterHelper
{
    public static function getCustomerMaxCode()
    {
        $prefix = 'AA';
        $max_code = DB::select("SELECT if(max(CAST(SUBSTRING(aa_no,3) as UNSIGNED)) is null , 0,max(CAST(SUBSTRING(aa_no, 3) as UNSIGNED))) as aa_no FROM as_artists WHERE aa_no LIKE '$prefix%'");

        if ($max_code) {
            $max_code = $max_code[0]->aa_no + 1;
            if (strlen($max_code) < 2)
                $max_code = str_pad($max_code, 3, '0', STR_PAD_LEFT);
        } else
            $max_code = '001';
        return $prefix . $max_code;
    }
    public static function encrypt($data)
    {
        return self::encriptionAlgorithm($data, 'encrypt');
    }

    public static function decrypt($data)
    {
        return self::encriptionAlgorithm($data, 'decrypt');
    }

    private static function encriptionAlgorithm($data, $type = 'encrypt')
    {
        $ciphering = "AES-128-CTR";
        $options = 0;
        $encryption_iv = '1234567891011121';
        $filename = public_path('encryption-key.php');
        $encryption_key = file_get_contents($filename);

        if ($type == 'encrypt')
            return openssl_encrypt($data, $ciphering, $encryption_key, $options, $encryption_iv);
        else
            return openssl_decrypt($data, $ciphering, $encryption_key, $options, $encryption_iv);
    }


    public static function getArtworkrMaxNo()
    {

        $model = 'as_artworks';
        $prefix = 'AAW';
        $prefix_length = strlen($prefix) + 1;

        $max_code = DB::select("SELECT if(max(CAST(SUBSTRING(asign_no,$prefix_length) as UNSIGNED)) is null , 0,max(CAST(SUBSTRING(asign_no, $prefix_length) as UNSIGNED))) as request_id FROM $model WHERE asign_no LIKE '$prefix%'");

        if ($max_code) {
            $max_code = $max_code[0]->request_id + 1;
            if (strlen($max_code) < $prefix_length - 1)
                $max_code = str_pad($max_code, $prefix_length, '0', STR_PAD_LEFT);
        } else
            $max_code = '001';
        return $prefix . $max_code;
    }


    public static function saveImage($input, $folder = 'images')
    {
        $image = '';
        if (request()->hasFile($input)) {
            $file = request()->file($input);
            $file_extension = $file->getClientOriginalName();
            $original_file_name = pathinfo($file_extension, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $original_file_name = str_replace(' ', '', $original_file_name);
            $name = $original_file_name . '_' . time() . '.' . $extension;
            $filePath = $folder . '/' . $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            $image = $filePath;
        }
        return $image;
    }
    public static function Search($query, $key, $search)
    {
        $customers = $query->get();
        $data = [];
        foreach ($customers as $customer) {
            if ($key == 'email') {
                if ($customer->email != "") {
                    if (str_contains($customer->email, $search)) {
                        $data[] = $customer->id;
                    }
                }
            } else if ($key == 'mobile') {
                if ($customer->mobile != "") {
                    if (str_contains($customer->mobile, $search)) {
                        $data[] = $customer->id;
                    }
                }
            } else if ($key == 'all')
                if ($customer->mobile != "" || $customer->email != "") {
                    if (str_contains($customer->mobile, $search) || str_contains($customer->email, $search)) {
                        $data[] = $customer->id;
                    }
                }
        }
        return $data;
    }
    public static function StatusSearch($query, $status_data)
    {
        $customers = $query->get();
        $data = [];
        foreach ($customers as $customer) {
            if ($status_data[0] == 'verified' && $customer->verify_status == 'verified') {
                $data[] = $customer->id;
            }
            if ($status_data[0] == 'unverified' && $customer->verify_status == 'unverified') {
                $data[] = $customer->id;
            }
            if ($status_data[0] == 'moderation' && $customer->verify_status == 'moderation') {
                $data[] = $customer->id;
            }
        }

        return $data;
    }
    public static function StatusSort($query, $sort)
    {
        $customers = $query->get();
        $data1 = [];
        $data2 = [];
        foreach ($customers as $customer) {
            if ($customer->verify_status == 'verified') {
                $data1[] = $customer->id;
            }
            if ($customer->verify_status == 'unverified') {
                $data2[] = $customer->id;
            }
        }
        if ($sort == 'asc') {
            $data = array_merge($data2, $data1);
        } else {
            $data = array_merge($data1, $data2);
        }
        return $data;
    }
}
