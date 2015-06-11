<?php

namespace jorenvanhocht\Blogify;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Database\DatabaseManager;

class Blogify
{

    /**
     * Holds the available char sets
     *
     * @var mixed
     */
    protected $char_sets;

    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    public function __construct(DatabaseManager $db, Config $config)
    {
        $this->char_sets = $config['blogify']['char_sets'];
        $this->db = $db->connection();
    }

    /**
     * @param null $table
     * @param null $field
     * @param bool $unique_in_table
     * @param int $min_length
     * @param int $max_length
     * @return string
     */
    public function makeHash(
        $table = null,
        $field = null,
        $unique_in_table = false,
        $min_length = 5,
        $max_length = 10
    ) {
        $hash = '';
        $charset = $this->char_sets[($unique_in_table) ? 'hash' : 'password'];

        // Generate a random length for the hash between the given min and max length
        $rand = rand($min_length, $max_length);

        for ($i = 0; $i < $rand; $i++) {
            $char = rand(0, strlen($charset));

            // When it's not the first char from the char_set make $minus equal to 1
            $minus = $char != 0 ? 1 : 0;

            // Add the character to the hash
            $hash .= $charset[$char - $minus];
        }

        if ($unique_in_table) {
            return $this->checkIfHashIsUnique($table, $field, $hash, $min_length, $max_length);
        } else {
            return $hash;
        }
    }

    /**
     * Generate a unique username with the users
     * lastname and firstname
     *
     * @param $lastname
     * @param $firstname
     * @param int $iteration
     * @return string
     */
    public function generateUniqueUsername($lastname, $firstname, $iteration = 0)
    {
        $username = strtolower(str_replace(' ', '', $lastname).substr($firstname, 0, 1));

        if ($iteration != 0) {
            $username = $username.$iteration;
        }

        $usernames = count($this->db->table('users')->where('username', '=', $username)->get());

        if ($usernames > 0) {
            return $this->generateUniqueUsername($lastname, $firstname, $iteration + 1);
        }

        return $username;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Helper methods
    ///////////////////////////////////////////////////////////////////////////

    /**
     * @param $table
     * @param $field
     * @param $hash
     * @param $min_length
     * @param $max_length
     * @return string
     */
    private function checkIfHAshIsUnique($table, $field, $hash, $min_length, $max_length)
    {
        if (! $this->db->table($table)->where($field, '=', $hash)->get()) {
            return $hash;
        } else {
            return $this->makeHAsh($table, $field, true, $min_length, $max_length);
        }
    }

}