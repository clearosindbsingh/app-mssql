<?php 

/**
 * MariaDB class.
 *
 * @category   apps
 * @package    mssql
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014-2016 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/mssql/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\mssql;

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('mssql');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Daemon as Daemon;
use \clearos\apps\base\Shell as Shell;
use \clearos\apps\network\Network_Utils as Network_Utils;

clearos_load_library('base/Daemon');
clearos_load_library('base/Shell');
clearos_load_library('network/Network_Utils');

// Exceptions
//-----------

use \Exception as Exception;
use \clearos\apps\base\Engine_Exception as Engine_Exception;
use \clearos\apps\base\Validation_Exception as Validation_Exception;

clearos_load_library('base/Engine_Exception');
clearos_load_library('base/Validation_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * mssql class.
 *
 * @category   apps
 * @package    mssql
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/mssql/
 */

class Mssql extends Daemon
{
    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    const COMMAND_MSSQLADMIN = '/opt/mssql/bin/mssql-conf';
    const COMMAND_MYSQLADMIN = '/usr/bin/mysqladmin';
    const COMMAND_MYSQL = '/usr/bin/mysql';
    const FILE_PHP_MYADMIN_CONFIGLET = '/etc/httpd/conf.d/phpMyAdmin.conf';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * MSSql constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

        parent::__construct('mssql');
        $this->command_filepath = "/usr/sbin/connect.exp";
        //$this->command_filepath = dirname(__FILE__)."/expect.exp";
    }
    function get_status1()
    {
        $options['validate_exit_code'] = FALSE;

        $shell = new Shell();
        $retval = $shell->execute(
            "systemctl status mssql-server", " ", TRUE, $options
        );
        $output = $shell->get_output();
        $running_status = 'stopped';
        if (strpos($output[2], '(running)') !== false) {
            $running_status = 'running';
        }
        return $running_status;
    }
    public function is_password_set($username, $hostname)
    {
        clearos_profile(__METHOD__, __LINE__);

        Validation_Exception::is_valid($this->validate_username($username));
        Validation_Exception::is_valid($this->validate_hostname($hostname));

        $options['validate_exit_code'] = FALSE;

        $shell = new Shell();
        $retval = $shell->execute(
            self::COMMAND_MYSQLADMIN, "-u'$username' -h'$hostname' --protocol=tcp status", FALSE, $options
        );

        if ($retval == 0)
            return FALSE;
        else
            return TRUE;
    }
    public function set_password_root($password)
    {


error_reporting(E_ALL);
ini_set('display_errors', 1);

$this->putPasswordFile(); 
$command = $this->command_filepath;
$shell = new Shell();
$retval = $shell->execute(
    $command, "set-sa-password", TRUE, $options
);
var_dump($shell->get_output());
var_dump($retval); die('df');

exec($command, $output);
echo '<pre>'; print_r($output);





die('complted');

       // $cmd1 = shell_exec("/opt/mssql/bin/mssql-conf -h");
       // print_r($cmd1); die('vv');

        clearos_profile(__METHOD__, __LINE__);

        Validation_Exception::is_valid($this->validate_password($password));

      
        
        
        $options['validate_exit_code'] = FALSE;
        $options['stdin'] = "Champ@1234";

        $shell = new Shell();
        $retval = $shell->execute(
            self::COMMAND_MSSQLADMIN, "set-sa-password", TRUE, $options
        );
        var_dump($shell->get_output());
        var_dump($retval); die('df');
        if ($retval == 0)
            return FALSE;
        else
            return TRUE;
    }

    /**
     * Checks that the password for localhost.
     *
     * @return boolean TRUE if set
     * @throws Engine_Exception
     */

    public function is_root_password_set()
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($this->is_password_set('root', 'localhost'))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Sets the database password for localhost and hostname.
     *
     * @param string $username     username
     * @param string $old_password old password
     * @param string $password     password
     * @param string $hostname     hostname
     *
     * @return void
     * @throws Engine_Exception, Validation_Exception
     */

    public function set_password($username, $old_password, $password, $hostname)
    {
        clearos_profile(__METHOD__, __LINE__);

        Validation_Exception::is_valid($this->validate_username($username));
        Validation_Exception::is_valid($this->validate_password($old_password));
        Validation_Exception::is_valid($this->validate_password($password));
        Validation_Exception::is_valid($this->validate_hostname($hostname));


        $this->putPasswordFile(); 
        $command = $this->command_filepath;
        $shell = new Shell();
        $retval = $shell->execute(
            $command, "set-sa-password", TRUE, $options
        );
        var_dump($shell->get_output());
        var_dump($retval); die('df');

        exec($command, $output);
        echo '<pre>'; print_r($output);


        die('complted');

        if ($old_password)
            $passwd_param = "-p'$old_password'";
        else
            $passwd_param = "";

        try {
            $options = array();
            $options['env'] = 'LANG=en_US'; 

            $shell = new Shell();
            $shell->Execute(
                self::COMMAND_MYSQLADMIN, 
                "-u'$username' $passwd_param -h'$hostname' --protocol=tcp password '$password'", FALSE, $options
            );
        } catch (Engine_Exception $e) {
            // KLUDGE: detect access denied so we can return a less cryptic message
            $output = $shell->get_last_output_line();
            $error = (preg_match('/Access denied/', $output)) ? lang('mariadb_access_denied') : $output;

            throw new Engine_Exception($error);
        }

        try {
            $shell->Execute(
                self::COMMAND_MYSQLADMIN, "-u$username $passwd_param -h$hostname --protocol=tcp flush-privileges"
            );
        } catch (Exception $e) {
            // Not fatal if it fails
        }
    }

    public function set_root_password($password,$system_password)
    {
        clearos_profile(__METHOD__, __LINE__);

        // set_password will handle the validation

        Validation_Exception::is_valid($this->validate_password($password));
        Validation_Exception::is_valid($this->validate_password($system_password));

        $this->stop_service();
        $this->putPasswordFile($password,$system_password); 
        $command = $this->command_filepath;




        $shell = new Shell();
        $options['validate_exit_code'] = FALSE;
        $retval = $shell->execute(
            $command, "set-sa-password", false, $options
        );
        $output = $shell->get_output();
        $error = (preg_match('/su: Authentication failure/', $output[2])) ? lang('mssql_system_password_wrong') : NULL;
        if($error)
        {
            throw new Engine_Exception($error);
        }
    }
    function start_service()
    {
        clearos_profile(__METHOD__, __LINE__);


        $this->startServiceFile(); 
        $command = $this->command_filepath;
        $shell = new Shell();
        $options['validate_exit_code'] = FALSE;
        $retval = $shell->execute(
            $command, "start-service", true, $options
        );
        //var_dump($shell->get_output());
        //var_dump($retval); die('df');
    }
    function stop_service()
    {
        clearos_profile(__METHOD__, __LINE__);

        $this->stopServiceFile($system_password); 
        $command = $this->command_filepath;
        $shell = new Shell();
        $options['validate_exit_code'] = false;
        $retval = $shell->execute(
            $command, "stop-service", TRUE, $options
        );
        //var_dump($shell->get_output());
        //var_dump($retval); die('df');
    }

    /**
     * Gets the URL for accessing PHP MyADMIN.
     *
     * @return string
     */

    public function get_url_php_myadmin()
    {
        clearos_profile(__METHOD__, __LINE__);
        // If a user upgrades to PHP 5.6, they also will have installed separate phpMyAdmin
        if (file_exists(self::FILE_PHP_MYADMIN_CONFIGLET))
            return "https://" . $_SERVER['SERVER_ADDR'] . "/phpMyAdmin";

        return "https://" . $_SERVER['SERVER_ADDR'] . ":81/mysql";
    }
    function get_download_url()
    {
        return "https://www.microsoft.com/en-us/download/details.aspx?id=50402";
    }

    ///////////////////////////////////////////////////////////////////////////////
    // V A L I D A T I O N   R O U T I N E S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Validates hostname.
     *
     * @param string $hostname hostname
     *
     * @return string error message if hostname is invalid
     */

    public function validate_hostname($hostname)
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! Network_Utils::is_valid_hostname($hostname))
            return lang('mariadb_hostname_invalid');
    }

    /**
     * Validates password.
     *
     * @param string $password password
     *
     * @return string error message if password is invalid
     */

    public function validate_password($password)
    {
        clearos_profile(__METHOD__, __LINE__);

        // TODO
    }

    /**
     * Validates password/verify.
     *
     * @param string $password password
     * @param string $verify   verify password
     *
     * @return string error message if passwords do not match
     */

    public function validate_password_verify($password, $verify)
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($password != $verify)
            return lang('mariadb_password_mismatch');
    }

    /**
     * Validates username.
     *
     * @param string $username username
     *
     * @return string error message if username is invalid
     */

    public function validate_username($username)
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! preg_match('/^([a-z0-9_\-\.\$]+)$/', $username))
            return lang('mariadb_username_invalid');
    }
    function putPasswordFile($password = "Champ@123",$system_password)
    {
        $file = $this->command_filepath;

        $commandc_code = '#!/usr/bin/expect -f';
        $commandc_code = $commandc_code.  "\n";
        $commandc_code = $commandc_code. ' set timeout 60';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' spawn su - root';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' expect "*?assword" { send "'.$system_password.'\r" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' expect "*#" { send "/opt/mssql/bin/mssql-conf set-sa-password\r" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' expect "*?new" { send "'.$password.'\r" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' expect "*?assword" { send "'.$password.'\r" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' expect "*?Confirm" { send "'.$password.'\r" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' expect "*?uccess" { send "/opt/mssql/bin/mssql-conf start-service\r" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. ' interact';
        $commandc_code = $commandc_code. "\n";

        
        //echo $file;
        //echo(file_get_contents($file,1)); die('ss');
        
        $f = fopen($file, "w") or die('File not open');
        fwrite($f, $commandc_code);
        fclose($f);
        chmod($file, 0777);

        // $commandc_code = '#!/usr/bin/expect -f';
        // $commandc_code = $commandc_code.  "\n";
        // $commandc_code = $commandc_code. ' set timeout 60';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' spawn /opt/mssql/bin/mssql-conf set-sa-password';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' expect "yes/no" {';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' send "yes\r"';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' expect "*?assword" { send "'.$password.'\r" }';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' } "*?new" { send "'.$password.'\r" }';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' expect "*?Confirm" { send "'.$password.'\r" }';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' expect "*?uccess" { send "/opt/mssql/bin/mssql-conf start-service\r" }';
        // $commandc_code = $commandc_code. "\n";
        // $commandc_code = $commandc_code. ' interact';
        // $commandc_code = $commandc_code. "\n";

        // $file = $this->command_filepath;
        // //echo $file;
        // //echo(file_get_contents($file));
        
        // $f = fopen($file, "w") or die('File not open');
        // fwrite($f, $commandc_code);
        // fclose($f);
        // chmod($file, 0777);
    }
    function startServiceFile()
    {
        $file = $this->command_filepath;
        $commandc_code = '#!/usr/bin/expect -f';

        $commandc_code = $commandc_code.  "\n";
        $commandc_code = $commandc_code. 'set timeout 60';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. 'spawn /opt/mssql/bin/mssql-conf start-service\n';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. 'expect "*#" { send "/opt/mssql/bin/mssql-conf start-service\n" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. 'interact';
        $commandc_code = $commandc_code. "\n";

        $f = fopen($file, "w") or die('File not open');
        fwrite($f, $commandc_code);
        fclose($f);
        chmod($file, 0777);
    }
    function stopServiceFile()
    {
        $file = $this->command_filepath;
        $commandc_code = '#!/usr/bin/expect -f';

        $commandc_code = $commandc_code.  "\n";
        $commandc_code = $commandc_code. 'set timeout 60';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. 'spawn /opt/mssql/bin/mssql-conf stop-service\n';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. 'expect "*#" { send "/opt/mssql/bin/mssql-conf stop-service\n" }';
        $commandc_code = $commandc_code. "\n";
        $commandc_code = $commandc_code. 'interact';
        $commandc_code = $commandc_code. "\n";

        $f = fopen($file, "w") or die('File not open');
        fwrite($f, $commandc_code);
        fclose($f);
        chmod($file, 0777);
    }
}
