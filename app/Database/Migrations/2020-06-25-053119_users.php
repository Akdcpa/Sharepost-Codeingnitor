<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
		$forge = \Config\Database::forge();
		$forge->addField([
			'id'=>[
				'type' => 'INTEGER',
				'constraint' => 11,
				'auto_increment' => true
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => 50, 
			],
			'email' => [
				'type' => 'VARCHAR',
				'constraint' => 100, 
			],
			'password' =>[
				'type' => 'VARCHAR',
				'constraint' => 100, 
			]
		]);
		$forge->addKey('id' , TRUE);
		$forge->createTable('users' , TRUE);

	}

	//--------------------------------------------------------------------

	public function down()
	{ 

		$forge->dropTable('users',TRUE);
	}
}
