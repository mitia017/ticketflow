<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up(){Schema::create('tickets',function(Blueprint $table){$table->id();$table->string('title');$table->text('description');$table->enum('status',['open','in_progress','resolved','closed'])->default('open');$table->enum('priority',['low','medium','high','critical'])->default('medium');$table->foreignId('created_by')->constrained('users');$table->foreignId('assigned_to')->nullable()->constrained('users');$table->timestamp('closed_at')->nullable();$table->timestamps();$table->index(['status','priority','assigned_to']);});}public function down(){Schema::dropIfExists('tickets');}};
