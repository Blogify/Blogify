<?php namespace jorenvanhocht\Blogify;

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
        $max_length = 20
    ) {
        $hash = '';

        // Generate a random length for the hash between the given min and max length
        $rand = rand($min_length, $max_length);

        for ($i = 0; $i < $rand; $i++) {
            $char = rand(0, strlen($this->char_sets[($unique_in_table) ? 'hash' : 'password']));

            // When it's not the first char from the char_set make $minus equal to 1
            $minus = $char != 0 ? 1 : 0;

            // Add the character to the hash
            $hash .= $this->char_sets[($unique_in_table) ? 'hash' : 'password'][$char - $minus];
        }

        if ($unique_in_table) {
            // Check if the hash doest not exist in the given table and column
            if (! $this->db->table($table)->where($field, '=', $hash)->get()) {
                return $hash;
            } else {
                return $this->makeHAsh($table, $field, true, $min_length, $max_length);
            }
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
     * @param int $itteration
     * @return string
     */
    public function generateUniqueUsername($lastname, $firstname, $itteration = 0)
    {
        $username = strtolower(str_replace(' ', '', $lastname) . substr($firstname, 0, 1));

        if ($itteration != 0) $username = $username . $itteration;

        $usernames = count($this->db->table('users')->where('username', '=', $username)->get());

        if ($usernames > 0) return $this->generateUniqueUsername($lastname, $firstname, $itteration + 1);

        return $username;
    }

}