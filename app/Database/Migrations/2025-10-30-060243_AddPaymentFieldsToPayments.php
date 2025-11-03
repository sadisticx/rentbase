<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToPayments extends Migration
{
    public function up()
    {
        $fields = [
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'amount',
            ],
            'reference_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'unique'     => true,
                'after'      => 'payment_method',
            ],
        ];

        $this->forge->addColumn('payments', $fields);

        // Modify payment_date to DATETIME instead of DATE
        $this->forge->modifyColumn('payments', [
            'payment_date' => [
                'type' => 'DATETIME',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('payments', ['payment_method', 'reference_number']);

        // Revert payment_date back to DATE
        $this->forge->modifyColumn('payments', [
            'payment_date' => [
                'type' => 'DATE',
            ],
        ]);
    }
}
