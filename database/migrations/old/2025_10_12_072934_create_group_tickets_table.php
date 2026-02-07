<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_tickets', function (Blueprint $table) {
            $table->id('GroupTicketID');
            
            $table->string('VoucherType')->nullable();
            $table->integer('VoucherNo')->nullable();

            $table->date('Date')->nullable();
            $table->string('SupplierID')->nullable();
            
            $table->string('PNR')->nullable();
            $table->string('Sector')->nullable();
            $table->date('DateOfDep')->nullable();
            $table->date('DateOfArr')->nullable();
            $table->string('AirlineName')->nullable();
            $table->string('FlightNo')->nullable();
            $table->decimal('Fare', 12, 2)->default(0);
            $table->decimal('Quantity', 12, 2)->default(1);
            $table->decimal('Payable', 12, 2)->default(0);
            
            
            $table->date('PaymentDueDate')->nullable();
            $table->string('PartyID')->nullable();

            $table->decimal('ExRate', 12, 2)->default(1);
            $table->string('CareOf')->nullable();
            $table->string('Remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_tickets');
    }
};
