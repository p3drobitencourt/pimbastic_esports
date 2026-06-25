<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJogoLiquidacaoFields extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('status', 'jogo')) {
            $this->db->query("ALTER TABLE jogo ADD COLUMN status ENUM('agendado', 'liquidado') NOT NULL DEFAULT 'agendado'");
        }

        if (! $this->db->fieldExists('resultado_final', 'jogo')) {
            $this->db->query("ALTER TABLE jogo ADD COLUMN resultado_final ENUM('vitoria_casa', 'empate', 'vitoria_fora') DEFAULT NULL AFTER status");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('resultado_final', 'jogo')) {
            $this->db->query('ALTER TABLE jogo DROP COLUMN resultado_final');
        }

        if ($this->db->fieldExists('status', 'jogo')) {
            $this->db->query('ALTER TABLE jogo DROP COLUMN status');
        }
    }
}
