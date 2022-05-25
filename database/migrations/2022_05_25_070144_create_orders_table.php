<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('channel_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('type',['N','C','P']); // N stand for new || C stand for change || P stand for promotionel
            $table->string('order_number');
            $table->string('total_price');
            $table->string('shipping_code')->nullable();
            $table->longText('internal_note')->nullable();
            $table->string('external_note')->nullable();
            $table->unsignedBigInteger('confirmed_by');
            $table->enum('order_status',['N','C','P','NR1','NR2','NR3','CA']);
            /*
             * N stand for new
             * C stand for confirmed
             * P stand for postboned
             * NR1 stand for no response 1
             * NR2 stand for no response 2
             * NR3 stand for no response 3
             * CA stand for canceled
             * */

            $table->enum('delivery_status',['N','T','S','P','SH','R','D','CA','NR','TR','OUT','UN','REP','REF','BA']);
            /*
             * N stand for new
             * T stand for treaty
             * S stand for sended
             * P stand for picked
             * SH stand for shipped
             * R stand for received
             * D stand for delivred
             * CA stand for canceled
             * NR stand for no response
             * TR stand for is in travel
             * OUT stand for out of area
             * UN stand for unreached
             * REP stand for reported
             * REF stand for refused
             * BA stand for back
             * */
            $table->enum('payment_status',['P','UNP']); // P stand for paid ||  UNP stand for unpaid
            $table->string('bar_code');
            $table->enum('status',['CS','UPS']); // CS stand for cross sell || UPS stand for up sell
            $table->unsignedBigInteger('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
