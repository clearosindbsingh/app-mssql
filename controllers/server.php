<?php

/**
 * mssql daemon controller.
 *
 * @category   apps
 * @package    mssql
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/mssql/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

require clearos_app_base('base') . '/controllers/daemon.php';

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * mssql daemon controller.
 *
 * @category   apps
 * @package    mssql
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/mssql/
 */

class Server extends Daemon
{
    /**
     * Server constructor.
     */

    function __construct()
    {
        parent::__construct('mssql', 'mssql');
    }

    /**
     * Full daemon status.
     *
     * @return json daemon status encoded in json
     */

    function full_status()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');

        $this->load->library('mssql/mssql');

        //$status['status'] = $this->mssql->set_password_root();
        $status['status'] = $this->mssql->get_status1();
        //$status['is_password_set'] = $this->mssql->is_root_password_set();

        echo json_encode($status);
    }
    function set_status($status)
    {
        $this->load->library('mssql/mssql');
        if($status == 'stop')
            $this->mssql->stop_service();
        else
            $this->mssql->start_service();
        $response['success'] = true;
        echo json_encode($response); 
    }
}
