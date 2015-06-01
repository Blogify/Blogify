<?php namespace jorenvanhocht\Blogify;

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

    public function __construct(DatabaseManager $db, $config)
    {
        $this->char_sets = $config['char_sets'];
        $this->db = $db->connection();
    }

    /**
     * Generate a unique hash
     *
     * @param $table
     * @param int $min_length
     * @param int $max_length
     * @return string
     */
    public function makeUniqueHash(
        $table,
        $field,
        $min_length = 5,
        $max_length = 20
    ) {
        $hash = '';

        // Generate a random length for the hash between the given min and max length
        $rand = rand($min_length, $max_length);

        for ($i = 0; $i < $rand; $i++) {
            $char = rand(0, strlen($this->char_sets['hash']));

            // When it's not the first char from the char_set make $minus equal to 1
            $minus = $char != 0 ? 1 : 0;

            // Add the character to the hash
            $hash .= $this->char_sets['hash'][$char - $minus];
        }

        // Check if the hash doest not exist in the given table and column
        if (! $this->db->table($table)->where($field, '=', $hash)->get()) {
            return $hash;
        }

        return $this->makeUniqueHash($table, $field, $min_length, $max_length);
    }

    /**
     * Generate a random password
     *
     * @return string
     */
    public function generatePassword()
    {
        $password   = '';
        $rand       = rand(4, 10);

        for($i = 0; $i < $rand; $i++) {
            $char = rand(0, strlen($this->char_sets['password'] ));

            $minus = $char != 0 ? 1 : 0;

            $password .= $this->char_sets['password'][$char - $minus];
        }
        return $password;
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