<?php

namespace App\Helpers;

use App\Models\ArtworkProtectActivityLog;
use App\Models\City;
use App\Models\Country;
use App\Models\MeasurementType;
use App\Models\Medium;
use App\Models\RejectedReason;
use App\Models\State;
use App\Models\Subject;
use App\Models\Surface;
use App\Models\Shape;
use App\Models\Technique;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UtilsHelper
{

    const AUTHENTICATOR = 31;
    const CONSERVATOR = 32;
    const FIELD_AGENT = 27;
    const SUPERVISOR = 17;
    const SERVICE_PROVIDER = 33;
    const ADMIN = 15;
    const SUPER_ADMIN_ROLE = 53;
    const INVENTORY_PRODUCT_ID = 1;

    const AUTHENTICITY_PRODUCT_ID = 2;

    public static function convertDateTimeToDay($date_time)
    {
        $date = Carbon::parse($date_time);
        $now = Carbon::now();
        $diff = $date->diffInDays($now);
        if ($diff == 1)
            return '1 day';
        else if ($diff == 0)
            return 'Today';
        else
            return $diff . ' days';
    }

    public static function convertDateTimeToDaySeconds($date_time)
    {
        if (!$date_time) {
            return '';
        }

        $date = Carbon::parse($date_time);
        $now = Carbon::now();
        $diff = $date->diffInDays($now);
        if ($diff == 0) {
            $diff = $date->diffInSeconds($now);
            if ($diff < 60)
                return $diff . ' S';
            else if ($diff < 3600)
                return floor($diff / 60) . ' M';
            else if ($diff < 86400)
                return floor($diff / 3600) . ' H';
            else
                return 'Today';
        } else
            return $diff . ' D';
    }

    public static function checkOnline($prefix = '')
    {
        $column = $prefix . 'last_login_at';
        return DB::raw("IF($column is null || $column <= NOW() - INTERVAL 20 MINUTE, false, true) AS is_online");
    }

    public static function storagePath($path = '')
    {
        $aws_bucket = config('filesystems.disks.s3.bucket');
        $aws_region = config('filesystems.disks.s3.region');
        $url = 'https://s3.' . $aws_region . '.amazonaws.com/' . $aws_bucket . '/';
        return $url . $path;
    }

    public static function getStoragePath()
    {
        return self::storagePath();
    }

    public static function pagination($result, $key = 'data')
    {
        return [
            'total_count' => $result->total(),
            $key => $result->items(),
        ];
    }

    public static function paginate($model, $key = false, $limit = false, $page = false)
    {

        $limit = request()->input('limit', $limit);
        $page = request()->input('page', $page);

        if ($limit && $page) {
            $result = $model->paginate($limit, ['*'], 'page', $page);
            if (!$key) return $result;
            return ['total_count' => $result->total(), $key => $result->items()];
        } else {
            $result = $model->get();
            if (!$key) return $result;
            return [$key => $result];
        }
    }

    public static function setOthersLast($data, $key = 'name')
    {

        $check_other_arr = ['Others', 'Other', 'others', 'other'];

        foreach ($check_other_arr as $value) {
            $other = array_search($value, array_column($data, $key));
            if ($other) {
                $other_data = $data[$other];
                unset($data[$other]);
                $data[] = $other_data;
                $data = array_values($data);
                break;
            }
        }

        return $data;
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

    public static function displayDate($date, $format = 'd M, Y')
    {
        if (!$date)
            return '';
        return date($format, strtotime($date));
    }

    public static function displayTime($time, $format = 'H:i:s')
    {
        if (!$time)
            return '';
        return date($format, strtotime($time));
    }

    public static function displayDateTime($date, $format = 'd M, Y h:i A')
    {
        if (!$date)
            return '';
        //        if (date('Y-m-d', strtotime($date)) == date('Y-m-d')) {
        //            return date('h:i A', strtotime($date));
        //        }
        return date($format, strtotime($date));
    }

    public static function displayNameAvatar($name = '')
    {
        if (!$name)
            return '';

        $nameParts = explode(' ', $name);
        $first_name = substr($nameParts[0], 0, 1);
        $last_name = isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0], 1, 1);
        return strtoupper($first_name . $last_name);
    }

    public static function getRoleColor($role_id)
    {

        $roles = [
            self::AUTHENTICATOR => 'authenticator',
            self::CONSERVATOR => 'conservator',
            self::FIELD_AGENT => 'field_agent',
            self::SUPERVISOR => 'supervisor',
            self::SERVICE_PROVIDER => 'service_provider',
            self::ADMIN => 'admin',
        ];

        $color = $roles[$role_id] ?? 'default';

        return ArtworkProtectActivityLog::COLOURS[$color];
    }

    public static function applyRoleFilter($role_id)
    {

        $check_arr = [
            self::AUTHENTICATOR => ['column' => 'authenticator_ids', 'verified_status' => ''],
            self::CONSERVATOR => ['column' => 'conservator_ids', 'verified_status' => 'authentication'],
            self::FIELD_AGENT => ['column' => 'field_agent_ids', 'verified_status' => 'inspection'],
        ];

        return $check_arr[$role_id] ?? false;
    }

    public static function roleWiseUser($data = [], $city = null, $check_city = false)
    {

        $roles = [
            'authenticator' => ["name" => 'Authenticator', "id" => self::AUTHENTICATOR],
            'field_agent' => ["name" => 'Field Agent', "id" => self::FIELD_AGENT],
            'conservator' => ["name" => 'Conservator', "id" => self::CONSERVATOR],
            'service_provider' => ["name" => 'Service Provider', "id" => self::SERVICE_PROVIDER],
            'supervisor' => ["name" => 'Supervisor', "id" => self::SUPERVISOR],
        ];

        $id = [];
        foreach ($data as $key => $value)
            $id[] = $roles[$value]['id'];

        if (count($id) == 0)
            $data = array_keys($roles);

        $users = User::query();
        if (count($id) > 0) {
            $users->whereIn('role_id', $id);
        }

        if ($check_city) {
            $users->whereNotNull('city_access');
            $city_id = City::where('name', "$city")->first();
            if ($city_id)
                $users->whereRaw("FIND_IN_SET($city_id->id, city_access)");
            else
                $users->whereRaw("FIND_IN_SET(0, city_access)");
        }

        $users = $users->orderBy('name')->get(['id', 'name', 'role_id', 'city_access']);

        $tmp = [];
        foreach ($users as $key => $value) {
            $tmp[$value->role_id][] = [
                'id' => $value->id,
                'name' => $value->name,
                'city_access' => $value->city_access,
            ];
        }
        $result = [];
        foreach ($data as $key => $value) {
            $result[$value] = $tmp[$roles[$value]['id']] ?? [];
        }
        return $result;
    }

    public static function masterData($params = [])
    {
        $result = [];

        if (isset($params['medium']) || count($params) == 0)
            $result['medium'] = Medium::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['surface']) || count($params) == 0)
            $result['surface'] = Surface::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['technique']) || count($params) == 0)
            $result['technique'] = Technique::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['shape']) || count($params) == 0)
            $result['shape'] = Shape::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['subject']) || count($params) == 0)
            $result['subject'] = Subject::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['measurement_type']) || count($params) == 0)
            $result['measurement_type'] = MeasurementType::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['country']) || count($params) == 0)
            $result['country'] = Country::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['state']) || count($params) == 0)
            $result['state'] = State::where(['status' => 1, 'country_id' => 102])->get(['id', 'name'])->toArray();
        if (isset($params['reasons']) || count($params) == 0)
            $result['reasons'] = RejectedReason::where('status', 1)->get(['id', 'name'])->toArray();
        if (isset($params['time']) || count($params) == 0)
            $result['time'] = [
                '08:00 am', '08:30 am', '09:00 am', '09:30 am', '10:00 am', '10:30 am', '11:00 am', '11:30 am', '12:00 pm', '12:30 pm', '01:00 pm', '01:30 pm', '02:00 pm', '02:30 pm', '03:00 pm', '03:30 pm', '04:00 pm', '04:30 pm', '05:00 pm', '05:30 pm', '06:00 pm', '06:30 pm', '07:00 pm'
            ];
        if (isset($params['time_range']) || count($params) == 0)
            $result['time_range'] = [
                '07:00 - 08:00 am',
                '08:00 - 09:00 am',
                '09:00 - 10:00 am',
                '10:00 - 11:00 am',
                '11:00 - 12:00 pm',
                '12:00 - 01:00 pm',
                '01:00 - 02:00 pm',
                '02:00 - 03:00 pm',
                '03:00 - 04:00 pm',
                '04:00 - 05:00 pm',
                '05:00 - 06:00 pm',
                '06:00 - 07:00 pm',
                '07:00 - 08:00 pm',
            ];

        return $result;
    }


    public static function convertSize($data)
    {

        $result = [];

        $convert_no = 2.54;

        if ($data['dimension_size'] == 'in') {
            $result['height'] = $data['height'];
            $result['width'] = $data['width'];
            $result['depth'] = $data['depth'];
            $result['diameter'] = $data['diameter'];
            $result['height_cm'] = round(($data['height'] * $convert_no), 2);
            $result['width_cm'] = round(($data['width'] * $convert_no), 2);
            $result['depth_cm'] = round(($data['depth'] * $convert_no), 2);
            $result['diameter_cm'] = round(($data['diameter'] * $convert_no), 2);
        } else if ($data['dimension_size'] == 'cm') {
            $result['height'] = round(($data['height'] / $convert_no), 2);
            $result['width'] = round(($data['width'] / $convert_no), 2);
            $result['depth'] = round(($data['depth'] / $convert_no), 2);
            $result['diameter'] = round(($data['diameter'] / $convert_no), 2);
            $result['height_cm'] = $data['height'];
            $result['width_cm'] = $data['width'];
            $result['depth_cm'] = $data['depth'];
            $result['diameter_cm'] = $data['diameter'];
        }
        return $result;
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


    public static function getMaxRequestNo($model, $prefix = 'LR')
    {
        $column = 'request_id';
        if ($model == 'as_labels')
            $prefix = 'LR';
        elseif ($model == 'as_stock_checks')
            $prefix = 'SC';
        elseif ($model == 'as_label_transfers') {
            $prefix = 'STO';
            $column = 'transfer_no';
        }

        $prefix_length = strlen($prefix) + 1;

        $max_code = DB::select("SELECT if(max(CAST(SUBSTRING($column,$prefix_length) as UNSIGNED)) is null , 0,max(CAST(SUBSTRING($column, $prefix_length) as UNSIGNED))) as request_id FROM $model WHERE $column LIKE '$prefix%'");
        if ($max_code) {
            $max_code = $max_code[0]->request_id + 1;
            if (strlen($max_code) < 6) {
                $max_code = str_pad($max_code, 6, '0', STR_PAD_LEFT);
            }
        } else
            $max_code = '000001';
        return $prefix . $max_code;
    }


    public static function imageLabel($name)
    {
        if ($name == 'featured')
            return 'Featured  Image';
        elseif ($name == 'front')
            return 'Front of Object';
        elseif ($name == 'back')
            return 'Back of Object';
        elseif ($name == 'additional-image')
            return 'Additional Image';
        else
            return $name;
    }
}
