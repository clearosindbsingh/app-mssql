<?php

/**
 * mssql ajax helpers.
 *
 * @category   apps
 * @package    mssql
 * @subpackage javascript
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/mssql/
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
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('mssql');

///////////////////////////////////////////////////////////////////////////////
// J A V A S C R I P T
///////////////////////////////////////////////////////////////////////////////

header('Content-Type:application/x-javascript');
?>

///////////////////////////////////////////////////////////////////////////
// M A I N
///////////////////////////////////////////////////////////////////////////

$(document).ready(function() {
    $('#mssql_running').hide();
    $('#mssql_no_password').hide();
    $('#mssql_password_ok').hide();

    clearosGetMysqlStatus();

    $(document).on('click','#btn_start_stop',function(){
        $this = $(this);
        var status = $(this).attr('data-status');
        var next_status = "start";
        if(status == 'running')
        {
            var next_status = "stop";
        }
        $this.attr("data-locked",1).html('<i class="fa fa-spinner fa-spin"></i>');
        $.getJSON("/app/mssql/server/set_status/"+next_status,function(data){
            $this.removeAttr("data-locked");
        });
    })
});


// Functions
//----------

function clearosGetMysqlStatus() {
    $.ajax({
        url: '/app/mssql/server/full_status',
        method: 'GET',
        dataType: 'json',
        success : function(payload) {
            handleMysqlForm(payload);
            window.setTimeout(clearosGetMysqlStatus, 1000);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            window.setTimeout(clearosGetMysqlStatus, 1000);
        }
    });
}

function handleMysqlForm(payload) {
    if (payload.status == 'running') {
        if($("#btn_start_stop").attr('data-locked') != 1)
            $('#btn_start_stop').attr('data-status',payload.status).text('Stop');
        $('#mssql_running').hide();
        $('#mssql_password_ok').show();

        return;
        $('#mssql_running').show();
        if (payload.is_password_set) {
            //$('#mssql_no_password').hide();
            $('#mssql_password_ok').show();
        } else {
            //$('#mssql_no_password').show();
            $('#mssql_password_ok').hide();
        }
    } else {
        if($("#btn_start_stop").attr('data-locked') != 1)
            $('#btn_start_stop').attr('data-status',payload.status).text('Start');
        $('#mssql_running').hide();
        //$('#mssql_no_password').hide();
        $('#mssql_password_ok').show();
    }

}
function checkServerStatus()
{
    $.getJSON("/app/mssql/server/full_status",function(data){
        if(data.status == "running")
        {
            $("#confirm_modal").modal('show');
        }
        else
        {
            submitFormFinal();
        }
    })
}
function submitFormFinal()
{
    //$("#mssql_password_set").submit();
    $(".theme-form-submit-update").html('<i class="fa fa-spinner fa-spin"></i>');
    $("#submit_btn").trigger('click');
}
// vim: syntax=javascript
