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
        'id' => $faker->uuid,
        'role_id' =>1,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'company_id' => function () {
            return factory(App\Company::class)->create()->id;
        },
        'name' => $faker->name,
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => '',
        'updated_at' => '',
        'picture' => '',
        'address' => $faker->address,
        'phone_number' => $faker->e164PhoneNumber,
        'fax' => $faker->e164PhoneNumber,
        'email2' => '',
        'email3' => '',
        'is_deleted' => 0,
        'deleted_by' => '',
        'deleted_at' => '',
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
    	'id' => $faker->uuid,
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
        'id' => $faker->uuid,
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
        'id' => $faker->uuid,
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
        'id' => $faker->uuid,
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
        'id' => $faker->uuid,
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

$factory->define(App\ExaminationAttach::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'examination_id' => function () {
            return factory(App\Examination::class)->create()->id;
        },
        'name' => $faker->word,
        'attachment' => $faker->word,
        'created_by' => '1',
        'updated_by' => '1',
        'created_at'=> Carbon\Carbon::now(),
        'updated_at'=> Carbon\Carbon::now(),
        'no' => $faker->word,
        'tgl'=> Carbon\Carbon::now(),
    ];
});

$factory->define(App\Questioner::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
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

$factory->define(App\Income::class, function (Faker\Generator $faker, $params) {
    if(isset($params['examination'])){
        $examination = $params['examination'];
    }else{
        $examination = factory(App\Examination::class)->create();
    }
    return [
        'id' => $faker->uuid,
        'company_id' => $examination->company_id,
        'inc_type' => '1',
        'reference_id' => $examination->id,
        'reference_number' => $faker->numberBetween(1000,9000),
        'price'  => $faker->numberBetween(1000,9000).'000',
        'tgl'=> Carbon\Carbon::now(),
        'created_by' => '1',
        'updated_by' => '1',
        'created_at'=> Carbon\Carbon::now(),
        'updated_at'=> Carbon\Carbon::now()
    ];
});

$factory->define(App\ExaminationCharge::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'device_name' => $faker->word,
        'stel' => $faker->word,
        'category' => 'Lab CPE',
        'duration' => $faker->numberBetween(5,60),
        'price' => $faker->numberBetween(100,900).'0000',
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at'=> Carbon\Carbon::now(),
        'updated_at'=> Carbon\Carbon::now(),
        'vt_price' => $faker->numberBetween(100,900).'0000',
        'ta_price' => $faker->numberBetween(100,900).'0000'
    ];
});

$factory->define(App\NewExaminationCharge::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->word,
        'description' => $faker->sentence(6, true),
        'valid_from' => Carbon\Carbon::now(),
        'is_implement' => 0,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
    ];
}); //NewExaminationChargeDetail

$factory->define(App\NewExaminationChargeDetail::class, function (Faker\Generator $faker, $params) {
    if(isset($params['examination_charges'])){
        $examination_charges = $params['examination_charges'];
    }else{
        $examination_charges = factory(App\ExaminationCharge::class)->create();
    }
    return [
        'id' => $faker->uuid,
        'new_exam_charges_id' => function () {
            $examination_charges = factory(App\NewExaminationCharge::class)->create();
            return $examination_charges->id;
        },
        'examination_charges_id' => $examination_charges->id,
        'price' => $examination_charges->price,
        'vt_price' => $examination_charges->vt_price,
        'ta_price'=> $examination_charges->ta_price,
        'new_price' => $faker->numberBetween(100,900).'0000',
        'new_vt_price' => $faker->numberBetween(100,900).'0000',
        'new_ta_price' => $faker->numberBetween(100,900).'0000',
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'device_name' => $faker->word,
        'stel' => $faker->word,
        'category' => 'Lab CPE',
        'duration' => $faker->numberBetween(5,60),
        'old_device_name' => $examination_charges->device_name,
        'old_stel' => $examination_charges->stel,
        'old_category' => $examination_charges->category,
        'old_duration' => $examination_charges->duration,
    ];
});

$factory->define(App\Equipment::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'examination_id' => function () {
            return factory(App\Examination::class)->create()->id;
        },
        'name' => $faker->word,
        'qty' => $faker->numberBetween(1,100),
        'unit' => $faker->word,
        'location' => 2,
        'pic' => $faker->word,
        'remarks' => $faker->word,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'description' => $faker->sentence(6, true),
        'no' => $faker->word
    ];
});


$factory->define(App\EquipmentHistory::class, function (Faker\Generator $faker, $params) {
    return [
        'id' => $faker->uuid,
        'location' => 2,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'examination_id' => function () {
            return factory(App\Equipment::class)->create()->examination_id;
        },
        'action_date' => Carbon\Carbon::now()
    ];
});


$factory->define(App\Question::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->word,
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now()
    ];
});


$factory->define(App\Questionpriv::class, function (Faker\Generator $faker, $params) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create(['role_id'=> 1])->id;
        },
        'question_id' => '',
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now()
    ];
});

$factory->define(App\Testimonial::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'examination_id' => function () {
            return factory(App\Examination::class)->create()->id;
        },
        'message' => $faker->sentence(6, true),
        'is_active' => 0,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now()
    ];
});


$factory->define(App\STEL::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'code' => 'test_code_'.$faker->word,
        'name' => 'test_name_'.$faker->word,
        'type' => 'test_type_'.$faker->word,
        'version' => 1,
        'year' => 2020,
        'price' =>  $faker->numberBetween(1000,9000).'000',
        'total' =>  $faker->numberBetween(1000,9000).'000',
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'attachment' => 'test_attachment_'.$faker->word,
        'stel_type' => 1,
    ];
});


$factory->define(App\STELSales::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'user_id' => function () {
            return factory(App\User::class)->create(['role_id'=>'2'])->id;
        },
        'invoice' => $faker->word,
        'name' => $faker->word,
        'exp' => $faker->word,
        'cvc' => $faker->numberBetween(100,900),
        'cvv' => $faker->numberBetween(100,900),
        'type' => $faker->word,
        'no_card' => $faker->numberBetween(100000,900000).$faker->numberBetween(100000,900000),
        'no_telp' => '0811'.$faker->numberBetween(1000000,9000000),
        'email' => $faker->safeEmail,
        'country' => 'Indonesia',
        'province' => 'Jawa',
        'city' => 'Bandung',
        'postal_code' => '14045',
        'birthdate' => '1993-01-01',
        'payment_method' => 1,
        'payment_status' => 1,
        'total' => $faker->numberBetween(1000,9000).'000',
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'payment_code' => $faker->numberBetween(100,900),
        'cust_price_payment' =>  $faker->numberBetween(1000,9000).'000',
        'id_kuitansi' =>'id_kuitansi_'.$faker->word,
        'faktur_file' => 'test_faktur_file_'.$faker->word,
        'PO_ID' => 'test_po_id_'.$faker->word,
        'BILLING_ID' => 'test_billing_id_'.$faker->word,
        'INVOICE_ID' => 'test_invoice_id_'.$faker->word,
    ];
});


$factory->define(App\STELSalesAttach::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'stel_sales_id' => function () {
            return factory(App\STELSales::class)->create()->id;
        },
        'attachment' => 'test_attachment_'.$faker->word,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now()
    ];
});


$factory->define(App\STELSalesDetail::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'stels_sales_id' => function () {
            return factory(App\STELSales::class)->create()->id;
        },
        'stels_id' => function () {
            return factory(App\STEL::class)->create()->id;
        },
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => Carbon\Carbon::now(),
        'updated_at' => Carbon\Carbon::now(),
        'qty' => $faker->numberBetween(100,900),
        'attachment' => 'test_attachment_'.$faker->word,
    ];
});