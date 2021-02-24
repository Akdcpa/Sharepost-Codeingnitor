<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Posts extends Migration
{
	
	public function up()
	{
		$forge = \Config\Database::forge();

		$forge->addField([
			'id'=>[
				'type' => 'INTEGER',
				'constraint' => 11,
				'auto_increment' => true,
				'unsigned'       => true,
			],
			'user_id'=>[
				'type' => 'INTEGER',
				'constraint' => 11, 
				'unsigned'   => true,
				'foreign_key' => array( 
                    'table' => 'users' ,
                    'field' => 'id' 
                )
			],
			'message' => [
				'type' => 'VARCHAR',
				'constraint' => 255, 
			],
			'url' => [
				'type' => 'VARCHAR', 
				'constraint' => 255, 
				'null'           => true, 

			],
			'types' =>[
				'type' => 'VARCHAR',
				'constraint' => 10,
				'null'           => true, 
			],
			'likes'=>[
				'type' => 'INTEGER',  
				'unsigned' => true,
				'null'  => true,
			],
			'dislikes'=>[
				'type' => 'INTEGER',
				'unsigned' => true, 
				'null'     => true,
			],
		]);
		$forge->addKey('id' , TRUE);
		$forge->createTable('posts' , TRUE);
	} 

	public function down()
	{ 
		$forge->dropTable('posts',TRUE);

	}
}
