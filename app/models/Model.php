<?php


/**
 * Model base class.
 */
class Model extends NObject
{
	/** @var NConnection */
	public $database;



	public function __construct(NConnection $database)
	{
		$this->database = $database;
	}

        public function getUsers()
        {
                return $this->database->table('users');
        }

        public function getEvents()
        {
                return $this->database->table('events');
        }

	public function createAuthenticatorService()
	{
		return new Authenticator($this->database->table('users'));
	}

}
