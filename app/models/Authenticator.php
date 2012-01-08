<?php



/**
 * Users authenticator.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class Authenticator extends NObject implements IAuthenticator
{
	/** @var NTableSelection */
	private $users;



	public function __construct(NTableSelection $users)
	{
		$this->users = $users;
	}

	/**
	 * Registers an user.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return NIdentity
	 */
	
    public function register($username, $password, $name)
    {
        
    }

	/**
	 * Performs an authentication
	 * @param  array
	 * @return NIdentity
	 * @throws NAuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		$row = $this->users->where('username', $username)->fetch();

		if (!$row) {
			throw new NAuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($row->password !== $this->calculateHash($password)) {
			throw new NAuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}

		unset($row->password);
		return new NIdentity($row->id, $row->role, $row->toArray());
	}



	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public function calculateHash($password)
	{
		return md5($password . str_repeat('*enter any random salt here*', 10));
	}

}
