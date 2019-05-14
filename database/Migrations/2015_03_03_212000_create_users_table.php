<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    protected $userTable;

    /**
     * @var array
     */
    protected $fields;

    public function __construct()
    {
        $this->userTable = config('blogify.blogify.users_table');

        $this->fillFieldsArray();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable($this->userTable)) {
            $this->createUsersTable();
        } else {
            $this->updateUsersTable();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists($this->userTable);
    }

    /**
     * Fill the fields array
     */
    private function fillFieldsArray()
    {
        $this->fields =  [
            'id' => [
                'type' => 'increments',
            ],
            'hash' => [
                'type' => 'string',
                'length' => 80,
                'extra' => 'unique',
            ],
//            'lastname' => [
//                'type' => 'string',
//                'length' => 30,
//            ],
//            'firstname' => [
//                'type' => 'string',
//                'length' => 30,
//            ],
//            'username' => [
//                'type' => 'string',
//                'length' => 30,
//                'extra' => 'unique'
//            ],
//            'email' => [
//                'type' => 'string',
//                'length' => 70,
//                'extra' => 'unique'
//            ],
//            'password' => [
//                'type' => 'string',
//                'length' => 100,
//            ],
//            'remember_token' => [
//                'type' => 'string',
//                'length' => 100,
//                'extra' => 'nullable'
//            ],
            'role_id' => [
                'type' => 'integer',
                'extra' => 'unsigned'
            ],
//            'profilepicture' => [
//                'type' => 'string',
//                'length' => 200,
//            ],
        ];
    }

    /**
     * Create a new Users table with
     * all the required fields
     */
    private function createUsersTable()
    {
        Schema::create($this->userTable, function ($table) {
            foreach ($this->fields as $field => $value) {
                $query = $table->$value['type']($field);

                if (isset($value['extra'])) {
                    $query->$value['extra']();
                }
            }

            $table->foreign('role_id')->references('id')->on('blogify_roles');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Add the not existing columns
     * to the existing users table
     */
    private function updateUsersTable()
    {
        Schema::table($this->userTable, function ($table) {
            foreach ($this->fields as $field => $value) {
                if (!Schema::hasColumn($this->userTable, $field)) {
                    $type  = $value['type'];
                    $query = $table->$type($field);

                    if (isset($value['extra'])) {
                        $extra = $value['extra'];
                        $query->$extra();
                    }

                    if ($field == 'role_id') {
                        $table->foreign('role_id')->references('id')->on('blogify_roles');
                    }
                }
            }

            if (!Schema::hasColumn($this->userTable, 'created_at') && !Schema::hasColumn($this->userTable, 'updated_at')) {
                $table->timestamps();
            }

            if (!Schema::hasColumn($this->userTable, 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

}
