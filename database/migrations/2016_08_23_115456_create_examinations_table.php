<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('examination_type_id');
            $table->uuid('company_id');
            $table->uuid('device_id');
            $table->uuid('examination_lab_id')->nullable();
            $table->string('spk_code')->nullable();
            $table->boolean('registration_status');
            $table->boolean('function_status')->default(0);
            $table->boolean('contract_status')->default(0);
            $table->boolean('spb_status');
            $table->boolean('payment_status');
            $table->boolean('spk_status');
            $table->boolean('examination_status');
            $table->boolean('resume_status');
            $table->boolean('qa_status');
            $table->boolean('certificate_status');
            $table->boolean('is_loc_test')->default(0);
            $table->string('jns_perusahaan')->nullable();
            $table->string('attachment')->nullable();
            $table->date('urel_test_date')->nullable();
			$table->date('cust_test_date')->nullable();
            $table->date('deal_test_date')->nullable();
            $table->date('function_date')->nullable();
            $table->boolean('function_test_date_approval')->default(0);
            $table->string('function_test_status_detail')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_spk_created')->default(0);
            $table->string('function_test_NO')->nullable();
			$table->boolean('function_test_TE')->default(0);
            $table->string('function_test_PIC')->nullable();
            $table->text('function_test_reason')->nullable();
            $table->date('contract_date')->nullable();
            $table->double('price', 15, 0)->default(0);
            $table->double('cust_price_payment', 15, 0)->default(0);
            $table->string('PO_ID')->nullable();
            $table->string('BILLING_ID')->nullable();
            $table->string('INVOICE_ID')->nullable();
            $table->unique('spk_code');
            $table->date('spk_date')->nullable();
            $table->string('spb_number')->nullable();
			$table->date('spb_date')->nullable();
            $table->date('examination_date')->nullable();
            $table->date('testing_start')->nullable();
			$table->date('testing_end')->nullable();
            $table->date('resume_date')->nullable();
            $table->boolean('qa_passed')->default(0);
            $table->date('qa_date')->nullable();
            $table->date('certificate_date')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('location')->default(0);
			$table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();

            $table->foreign('examination_type_id')->references('id')->on('examination_types');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('examination_lab_id')->references('id')->on('examination_labs');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('examinations');
    }
}
