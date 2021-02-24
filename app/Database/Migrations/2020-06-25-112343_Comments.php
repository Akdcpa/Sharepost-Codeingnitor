<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Comments extends Migration
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
			'user_id'=>[
				'type' => 'INTEGER',
				'constraint' => 11, 
				'foreign_key' => array( 
                    'table' => 'users' ,
                    'field' => 'id' 
                )
			],
			'post_id'=>[
				'type' => 'INTEGER',
				'constraint' => 11, 
				'foreign_key' => array( 
                    'table' => 'posts' ,
                    'field' => 'id' 
                )
			],
			'comment' => [
				'type' => 'VARCHAR',
				'constraint' => 255, 
			],
		]);
		$forge->addKey('id',TRUE);
		$forge->createTable('comments' , TRUE); 
	}
 

	public function down()
	{
		$forge->dropTable('comments',TRUE);
	}
}
