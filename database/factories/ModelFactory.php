<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'id' => str_random(2),
        'role_id' =>1,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\CalibrationCharge::class, function (Faker\Generator $faker) {
    return [
        'device_name' => str_random(10),
        'price' => mt_rand(0,10000),
        'is_active' => mt_rand(0,1) 
    ];
});

$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
    	'id' => $faker->numberBetween(1000,9000),
        'name' => $faker->word,
        'address' => str_random(10),
        'city' => str_random(10),
        'email' => $faker->safeEmail,
        'postal_code' => str_random(10),
        'phone_number' =>mt_rand(0,10000),
        'fax' => str_random(10),
        'npwp_number' => mt_rand(0,10000),
        'npwp_file' => str_random(10),
        'siup_number' => str_random(10),
        'siup_file' => str_random(10), 
        'siup_date' => str_random(10), 
        'qs_certificate_number' => str_random(10),
        'qs_certificate_file' => str_random(10),
        'qs_certificate_date' => str_random(10), 
        'is_active' => mt_rand(0,1),
        'created_by' => mt_rand(0,1),
        'created_at' => Carbon\Carbon::now(),
        'updated_by' => mt_rand(0,1),
        'updated_at' => Carbon\Carbon::now(),
    ];
});

$factory->define(App\Feedback::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1000,9000),
        'email' => $faker->safeEmail,
        'subject' => $faker->sentence(2, true),
        'message' => $faker->sentence(6, true),
        'created_by' => mt_rand(0,1),
        'created_at' => Carbon\Carbon::now(),
        'updated_by' => mt_rand(0,1),
        'updated_at' => Carbon\Carbon::now(),
        'status' => 0,
        'category' => $faker->word
    ];
});

$factory->define(App\Device::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1000,9000),
        'name' => $faker->word,
        'mark' => $faker->word,
        'capacity' => $faker->word,
        'manufactured_by' => $faker->word,
        'serial_number' => $faker->numberBetween(1000,9000),
        'model' => $faker->word,
        'test_reference' => $faker->word,
        'certificate' => $faker->word,
        'status' => '1',
        'valid_from' => Carbon\Carbon::now(),
        'valid_thru' => Carbon\Carbon::now(),
        'is_active' => '1',
        'created_by' => '1',
        'updated_by' => '1',
        'updated_at' => Carbon\Carbon::now(),
        'cert_number' => $faker->numberBetween(1000,9000)
    ];
});

$factory->define(App\ExaminationLab::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1000,9000),
        'name' => $faker->word,
        'description' => $faker->sentence(6, true),
        'is_active' => '1',
        'created_by' => '1',
        'updated_by' => '1',
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'lab_code' => $faker->bothify(5),
        'lab_init' => $faker->bothify(10),
        'close_until' => Carbon\Carbon::now(),
        'open_at' => Carbon\Carbon::now()
    ];
});

$factory->define(App\Examination::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1000,9000),
         'examination_type_id' => $faker->numberBetween(1,4),
        'company_id' => function () {
            return factory(App\Company::class)->create()->id;
        },
        'device_id' => function () {
            return factory(App\Device::class)->create()->id;
        },
        'examination_lab_id' => function () {
            return factory(App\ExaminationLab::class)->create()->id;
        },
        'spk_code' => $faker->word,
        'registration_status' => '1',
        'function_status' => '1',
        'contract_status' => '1',
        'spb_status' => '1',
        'payment_status' => '1',
        'spk_status' => '0',
        'examination_status'  => '0',
        'resume_status' => '0',
        'qa_status'  => '0',
        'certificate_status'  => '0',
        'created_by' => '1',
        'updated_by' => '1',
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'attachment' => $faker->word,
        'examination_date'  => Carbon\Carbon::now(),
        'resume_date'  => Carbon\Carbon::now(),
        'qa_date'  => Carbon\Carbon::now(),
        'certificate_date'  => Carbon\Carbon::now(),
        'jns_perusahaan' => $faker->word,
        'price' => $faker->numberBetween(1000,9000),
        'spk_date' => Carbon\Carbon::now(),
        'keterangan' => $faker->sentence(3, true),
        'urel_test_date' => Carbon\Carbon::now(),
        'cust_test_date' => Carbon\Carbon::now(),
        'deal_test_date' => Carbon\Carbon::now(),
        'catatan' => $faker->sentence(3, true),
        'function_date' => Carbon\Carbon::now(),
        'contract_date' => Carbon\Carbon::now(),
        'testing_start' => Carbon\Carbon::now(),
        'testing_end' => Carbon\Carbon::now(),
        'spb_number' => $faker->numberBetween(1000,9000),
        'spb_date' => Carbon\Carbon::now(),
        'qa_passed' => '1',
        'is_spk_created' => '1',
        'function_test_TE' => '1',
        'function_test_PIC' => $faker->word,
        'function_test_NO' => $faker->numberBetween(1000,9000),
        'function_test_reason' => $faker->sentence(3, true),
        'cust_price_payment' => $faker->numberBetween(1000,9000),
        'location' => $faker->numberBetween(1000,9000),
        'is_loc_test' => '1',
        'function_test_date_approval' => '1',
        'function_test_status_detail' => $faker->word,
        'PO_ID' => $faker->word,
        'BILLING_ID' => $faker->word,
        'INVOICE_ID' => $faker->word,
    ];
});

$factory->define(App\Questioner::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1000,9000),
        'examination_id' => function () {
            return factory(App\Examination::class)->create()->id;
        },
        'questioner_date' => Carbon\Carbon::now(),
        'quest1_eks' => 1,
        'quest1_perf' => 1,
        'quest2_eks' => 1,
        'quest2_perf' => 1,
        'quest3_eks' => 1,
        'quest3_perf' => 1,
        'quest4_eks' => 1,
        'quest4_perf' => 1,
        'quest5_eks' => 1,
        'quest5_perf' => 1,
        'quest6' => $faker->sentence(2, true),
        'quest7_eks' => 1,
        'quest7_perf' => 1,
        'quest8_eks' => 1,
        'quest8_perf' => 1,
        'quest9_eks' => 1,
        'quest9_perf' => 1,
        'quest10_eks' => 1,
        'quest10_perf' => 1,
        'quest11_eks' => 1,
        'quest11_perf' => 1,
        'quest12_eks' => 1,
        'quest12_perf' => 1,
        'quest13_eks' => 1,
        'quest13_perf' => 1,
        'quest14_eks' => 1,
        'quest14_perf' => 1,
        'quest15_eks' => 1,
        'quest15_perf' => 1,
        'quest16_eks' => 1,
        'quest16_perf' => 1,
        'quest17_eks' => 1,
        'quest17_perf' => 1,
        'quest18_eks' => 1,
        'quest18_perf' => 1,
        'quest19_eks' => 1,
        'quest19_perf' => 1,
        'quest20_eks' => 1,
        'quest20_perf' => 1,
        'quest21_eks' => 1,
        'quest21_perf' => 1,
        'quest22_eks' => 1,
        'quest22_perf' => 1,
        'quest23_eks' => 1,
        'quest23_perf' => 1,
        'quest24_eks' => 1,
        'quest24_perf' => 1,
        'quest25_eks' => 1,
        'quest25_perf' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at'=> Carbon\Carbon::now(),
        'updated_at'=> Carbon\Carbon::now(),
        'complaint_date' => Carbon\Carbon::now(),
        'complaint' => $faker->sentence(2, true)
    ];
});

$factory->define(App\Income::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1000,9000),
        'company_id' => function () {
            return factory(App\Company::class)->create()->id;
        },
        'inc_type' => '1',
        'reference_id' => function () {
            return factory(App\Examination::class)->create()->id;
        },
        'reference_number' => $faker->numberBetween(1000,9000),
        'price'  => $faker->numberBetween(1000,9000).'000',
        'tgl'=> Carbon\Carbon::now(),
        'created_by' => '1',
        'updated_by' => '1',
        'created_at'=> Carbon\Carbon::now(),
        'updated_at'=> Carbon\Carbon::now()
    ];
});