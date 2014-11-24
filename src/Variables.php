<?php namespace Devfactory\Variables;

use Illuminate\Support\Facades\Config;
use Schema;

use Devfactory\Variables\Models\Variable;

class Variables {

  protected $key;

  protected $value;

  /**
   * Retrieve one of the variables, taking the value from the DB if it is
   * overridden, otherwise it will take the value from the config file
   *
   * @param string $key
   *  The key of the variable to get the value of
   *
   * @param bool   $get_array
   *  TRUE if you want to get the full array entry for the variable
   *  rather then just the value.
   *
   * @return mixed
   *  Returns the value of the variable, or the full array of the variable
   */
  public function get($key, $force_config_file = FALSE) {
    $this->key = $key;

    if (!$this->keyExists()) {
      return '';
    }

    return $this->getValue($force_config_file);
  }

  /**
   * Perform retrieval of the value of the variable
   *
   * @param bool $force_config_file
   *  TRUE if you want to get the full array entry for the variable
   *  rather then just the value.
   *
   * @return string
   *  The value of the variable
   */
  private function getValue($force_config_file = FALSE) {
    $variable_config = Config::get('variables::variables.' . $this->key);

    if ($force_config_file) {
      return $variable_config['value'];
    }

    $variable_db = Variable::where('key', '=', $this->key)->first();

    if (!empty($variable_db)) {
      $variable_config['value'] = $variable_db->value;
    }

    return $variable_config['value'];
  }

  /**
   * Get all the variables set in the config file, replacing with those
   * overridden in the database.
   *
   * @return array
   *  An array of all the Variables with value other fields from config file
   */
  public function getAll() {
    $variables = Config::get('variables::variables');

    foreach ($variables as $key => &$variable) {
      $variable_db = Variable::where('key', '=', $key)->first();

      if (!empty($variable_db)) {
        $variable['value'] = $variable_db->value;
      }
    }

    return $variables;
  }

  /**
   * Override the value set in config file by writting it to the corresponding
   * place in the database.
   *
   * @param string $key
   *  The key of the variable to override
   *
   * @param string $value
   *  The value to give to the variable
   *
   * @return bool
   *  TRUE if the variable was saved, otherwise FALSE
   */
  public function set($key, $value) {
    $this->key = $key;
    $this->value = $value;

    if (!$this->keyExists()) {
      return '';
    }

    return $this->setValue();
  }

  /**
   * Perform checks if we are updating or creating a value
   *
   * @return bool
   *  TRUE if the variable is sucessfully set, otherwise FALSE
   */
  private function setValue() {
    $variable_db = Variable::where('key', '=', $this->key)->first();

    // Variable does not exist, create if not the same as config file
    if (empty($variable_db)) {
      return $this->createVariable();
    }

    // Variable exists
    return $this->updateVariable($variable_db);
  }

  /**
   * Create a variable in the database
   *
   * @return bool
   *  TRUE if the variable has been created, otherwise FALSE
   */
  private function createVariable() {
    $variable_config = Config::get('variables::variables.' . $this->key);

    // Value is same as config, so don't create
    if ($this->value == $variable_config['value']) {
      return TRUE;
    }

    // Variable same as the one in config, so don't create DB entry
    $variable = new Variable;

    $variable->key   = $this->key;
    $variable->value = $this->value;

    return $variable->save();
  }

  /**
   * Update an entry for a variable in the database
   *
   * @param object $variable
   *  The Eloquent model object for a variable
   *
   * @return bool
   *  TRUE id the variable was updated, otherwise FALSE
   */
  private function updateVariable($variable) {
    $variable_config = Config::get('variables::variables.' . $this->key);

    // New value is same as config, so delete
    if ($this->value == $variable_config['value']) {
      return $variable->delete();
    }
    // New value differs from config, so modify DB
    else {
      $variable->value = $this->value;
      return $variable->save();
    }
  }

  /**
   * Delete the variable in the DB
   *
   * @param string $key
   *  The key of variable to delete
   *
   * @return bool
   *  TRUE if variable deleted from DB, otherwise FALSE
   */
  public function remove($key) {
    $variable = Variable::where('key', '=', $key)->first();

    if (!empty($variable)) {
      return $variable->delete();
    }

    return FALSE;
  }

  /**
   * Helper function to check if the key exists in the config file
   *
   * @return bool
   *  TRUE if found, otherwise FALSE
   */
  private function keyExists() {
    if (is_null(Config::get('variables::variables.' . $this->key))) {
      return FALSE;
    }

    return TRUE;
  }

}
