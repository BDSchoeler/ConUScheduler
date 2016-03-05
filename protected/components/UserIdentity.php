<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

	private $_id;
	private $_netName;
	private $_firstName;
	private $_lastName;

	public function authenticate()
	{
		$record=User::model()->findByAttributes(array('username'=>$this->username));
		if($record===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!  (md5($this->password) == $record->password  ))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$record->ID;

			$this->_netName = $record->netName; // set net name
			$this->_firstName = $record->firstname;
			$this->_lastName = $record->lastname;

			$this->setState('netName', $record->netName); // stores netname in user session variable
			$this->setState('userID', $record->ID); // stores user's ID in user session variable

			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}

	/**
	 * @return mixed returns user's unique ID stored in the database
	 */
	public function getId()
	{
		return $this->_id;
	}


	/**
	 * @return mixed returns user's net name
	 */
	public function getNameName()
	{
		return $this->_netName;
	}

	public function getFirstName()
	{
		return $this->_firstName;
	}

	public function getLastName()
	{
		return $this->_lastName;
	}
}